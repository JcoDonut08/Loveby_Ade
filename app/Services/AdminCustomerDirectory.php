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
            ->where(fn ($query) => $query
                ->where('role', '!=', 'admin')
                ->orWhereNull('role'))
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

        $activeAfter = now()->subMinutes((int) config('session.lifetime', 120))->timestamp;

        return DB::table('sessions')
            ->whereIn('user_id', $userIds)
            ->where('last_activity', '>=', $activeAfter)
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
        $lastActiveAt = collect([$lastSessionAt, $user->last_active_at, $user->updated_at])
            ->filter()
            ->sortDesc()
            ->first();
        $isActiveNow = $lastSessionAt instanceof CarbonInterface;

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
            'activity' => $this->activityFor($user, $lastActiveAt, $isActiveNow),
            'lastActive' => $isActiveNow ? 'Active now' : $this->lastActiveLabel($lastActiveAt),
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
            'isDelivered' => $order->status === Order::STATUS_DELIVERED,
        ];
    }

    /**
     * @return array<int, string>
     */
    private function activityFor(User $user, ?CarbonInterface $lastActiveAt, bool $isActiveNow): array
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
            $isActiveNow ? 'Customer is active now' : ($lastActiveAt instanceof CarbonInterface ? 'Last activity '.$this->lastActiveLabel($lastActiveAt) : 'Created account without purchase activity yet'),
        ])
            ->filter()
            ->values()
            ->all();
    }

    private function segmentFor(User $user): string
    {
        $spent = $user->orders
            ->where('status', Order::STATUS_DELIVERED)
            ->sum(fn (Order $order): float => (float) $order->total);

        if ($spent >= 1000) {
            return 'top_spender';
        }

        if ($spent > 0) {
            return 'regular_customer';
        }

        return 'new_customer';
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
