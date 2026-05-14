<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminCustomerDirectory
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function customers(): array
    {
        $users = User::query()
            ->where('role', '!=', 'admin')
            ->with(['orders' => fn ($query) => $query
                ->with('items')
                ->latest()])
            ->latest()
            ->get();

        $latestSessions = $this->latestSessionsFor($users->pluck('id'));

        return $users
            ->map(fn (User $user): array => $this->customerPayload($user, $latestSessions[$user->id] ?? null))
            ->values()
            ->all();
    }

    /**
     * @param  Collection<int, int>  $userIds
     * @return array<int, CarbonInterface>
     */
    private function latestSessionsFor(Collection $userIds): array
    {
        if (! Schema::hasTable('sessions') || $userIds->isEmpty()) {
            return [];
        }

        return DB::table('sessions')
            ->whereIn('user_id', $userIds)
            ->selectRaw('user_id, MAX(last_activity) as last_activity')
            ->groupBy('user_id')
            ->get()
            ->mapWithKeys(fn ($session): array => [
                (int) $session->user_id => now()->setTimestamp((int) $session->last_activity),
            ])
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function customerPayload(User $user, ?CarbonInterface $lastSessionAt): array
    {
        $orders = $user->orders;
        $lastOrderAt = $orders->max('created_at');
        $lastActiveAt = collect([$lastSessionAt, $lastOrderAt, $user->updated_at])
            ->filter()
            ->sortDesc()
            ->first();

        return [
            'id' => 'CUS-'.str_pad((string) $user->id, 4, '0', STR_PAD_LEFT),
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->contact_number ?: 'No phone number',
            'avatar' => $user->profilePhotoUrl() ?: asset('images/lovebyadelogo.png'),
            'orders' => $orders
                ->map(fn (Order $order): array => $this->orderPayload($order))
                ->values()
                ->all(),
            'activity' => $this->activityFor($user, $lastActiveAt),
            'lastActive' => $this->lastActiveLabel($lastActiveAt),
            'joined' => $user->created_at?->format('F j, Y') ?? 'Unknown',
            'segment' => $this->segmentFor($user),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function orderPayload(Order $order): array
    {
        return [
            'id' => $order->order_number,
            'date' => $order->created_at?->format('M j, Y') ?? 'Unknown',
            'items' => $order->items
                ->map(fn ($item): string => $item->product_title.' x'.$item->quantity)
                ->join(', '),
            'total' => (float) $order->total,
            'status' => Str::headline($order->status),
        ];
    }

    /**
     * @return array<int, string>
     */
    private function activityFor(User $user, ?CarbonInterface $lastActiveAt): array
    {
        $latestOrder = $user->orders->first();
        $favoriteCategory = $user->orders
            ->flatMap->items
            ->groupBy('category')
            ->sortByDesc(fn (Collection $items): int => $items->sum('quantity'))
            ->keys()
            ->first();

        return collect([
            $latestOrder instanceof Order ? 'Placed order '.$latestOrder->order_number.' '.$this->lastActiveLabel($latestOrder->created_at) : null,
            is_string($favoriteCategory) ? 'Frequently buys '.$favoriteCategory : null,
            $lastActiveAt instanceof CarbonInterface ? 'Last activity '.$this->lastActiveLabel($lastActiveAt) : 'Created account without purchase activity yet',
        ])
            ->filter()
            ->values()
            ->all();
    }

    private function segmentFor(User $user): string
    {
        if ($user->created_at?->greaterThanOrEqualTo(now()->subDays(7)) === true) {
            return 'new_customer';
        }

        $spent = $user->orders->sum(fn (Order $order): float => (float) $order->total);

        if ($spent >= 500) {
            return 'top_spender';
        }

        return 'active_today';
    }

    private function lastActiveLabel(?CarbonInterface $lastActiveAt): string
    {
        if (! $lastActiveAt instanceof CarbonInterface) {
            return 'No activity yet';
        }

        if ($lastActiveAt->isToday()) {
            return $lastActiveAt->diffForHumans();
        }

        if ($lastActiveAt->isYesterday()) {
            return 'Yesterday';
        }

        return $lastActiveAt->format('M j, Y');
    }
}
