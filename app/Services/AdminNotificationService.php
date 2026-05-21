<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AdminNotificationService
{
    /**
     * @param  array<int, string>  $readIds
     * @return Collection<int, array{id: string, title: string, message: string, time: string, icon: string, tone: string, unread: bool, occurred_at: CarbonInterface|null, url: string}>
     */
    public function notifications(array $readIds = []): Collection
    {
        return collect()
            ->merge($this->orderNotifications($readIds))
            ->merge($this->lowStockNotifications($readIds))
            ->merge($this->customerNotifications($readIds))
            ->merge($this->reviewNotifications($readIds))
            ->sortByDesc('occurred_at')
            ->values();
    }

    /**
     * @return array<int, string>
     */
    public function notificationIds(): array
    {
        return $this->notifications()
            ->pluck('id')
            ->all();
    }

    /**
     * @param  array<int, string>  $readIds
     */
    public function unreadCount(array $readIds = []): int
    {
        return $this->notifications($readIds)
            ->where('unread', true)
            ->count();
    }

    /**
     * @param  array<int, string>  $readIds
     * @return Collection<int, array{id: string, title: string, message: string, time: string, icon: string, tone: string, unread: bool, occurred_at: CarbonInterface|null, url: string}>
     */
    private function orderNotifications(array $readIds): Collection
    {
        return Order::query()
            ->latest('updated_at')
            ->limit(30)
            ->get()
            ->map(fn (Order $order): array => $this->notification(
                id: "admin-order-{$order->id}-{$order->status}",
                title: $this->orderTitle($order),
                message: $this->orderMessage($order),
                icon: $this->orderIcon($order),
                tone: $this->orderTone($order),
                occurredAt: $order->updated_at,
                url: route('admin.orders', [
                    'status' => $order->status,
                    'search' => $order->order_number,
                ]),
                readIds: $readIds,
            ));
    }

    /**
     * @param  array<int, string>  $readIds
     * @return Collection<int, array{id: string, title: string, message: string, time: string, icon: string, tone: string, unread: bool, occurred_at: CarbonInterface|null, url: string}>
     */
    private function lowStockNotifications(array $readIds): Collection
    {
        return Product::query()
            ->where('is_active', true)
            ->where('stock', '<=', 10)
            ->orderBy('stock')
            ->orderBy('title')
            ->limit(20)
            ->get()
            ->map(fn (Product $product): array => $this->notification(
                id: "admin-product-{$product->id}-low-stock",
                title: 'Low stock alert',
                message: $product->title.' only '.number_format($product->stock).' '.str('unit')->plural((int) $product->stock).' left',
                icon: 'alert',
                tone: 'orange',
                occurredAt: $product->updated_at,
                url: route('admin.products', ['search' => $product->title]),
                readIds: $readIds,
            ));
    }

    /**
     * @param  array<int, string>  $readIds
     * @return Collection<int, array{id: string, title: string, message: string, time: string, icon: string, tone: string, unread: bool, occurred_at: CarbonInterface|null, url: string}>
     */
    private function customerNotifications(array $readIds): Collection
    {
        return User::query()
            ->where(fn ($query) => $query
                ->where('role', '!=', 'admin')
                ->orWhereNull('role'))
            ->latest()
            ->limit(20)
            ->get()
            ->map(fn (User $user): array => $this->notification(
                id: "admin-customer-{$user->id}-registered",
                title: 'New customer registered',
                message: $user->name.' joined Loveby_Ade',
                icon: 'user',
                tone: 'blue',
                occurredAt: $user->created_at,
                url: route('admin.customers'),
                readIds: $readIds,
            ));
    }

    /**
     * @param  array<int, string>  $readIds
     * @return Collection<int, array{id: string, title: string, message: string, time: string, icon: string, tone: string, unread: bool, occurred_at: CarbonInterface|null, url: string}>
     */
    private function reviewNotifications(array $readIds): Collection
    {
        return ProductReview::query()
            ->with('product')
            ->latest()
            ->limit(20)
            ->get()
            ->map(fn (ProductReview $review): array => $this->notification(
                id: "admin-review-{$review->id}-received",
                title: 'Review received',
                message: ($review->product?->title ?? 'A product').' earned '.$review->rating.' '.str('star')->plural($review->rating),
                icon: 'review',
                tone: 'cream',
                occurredAt: $review->created_at,
                url: route('admin.products', ['search' => $review->product?->title]),
                readIds: $readIds,
            ));
    }

    private function orderTitle(Order $order): string
    {
        return match ($order->status) {
            Order::STATUS_PREPARING => 'Order approved',
            Order::STATUS_OUT_FOR_DELIVERY => 'Order out for delivery',
            Order::STATUS_DELIVERED => 'Order delivered',
            Order::STATUS_CANCELLED => 'Order cancelled',
            default => 'New order received',
        };
    }

    private function orderMessage(Order $order): string
    {
        $customer = $order->full_name ?: 'Customer';
        $total = $this->money((float) $order->total);

        return match ($order->status) {
            Order::STATUS_PREPARING => "{$order->order_number} for {$customer} is being prepared - {$total}",
            Order::STATUS_OUT_FOR_DELIVERY => "{$order->order_number} is out for delivery to {$customer} - {$total}",
            Order::STATUS_DELIVERED => "{$order->order_number} was delivered to {$customer} - {$total}",
            Order::STATUS_CANCELLED => "{$order->order_number} for {$customer} was cancelled",
            default => "{$order->order_number} from {$customer} - {$total}",
        };
    }

    private function orderIcon(Order $order): string
    {
        return match ($order->status) {
            Order::STATUS_OUT_FOR_DELIVERY => 'delivery',
            Order::STATUS_DELIVERED => 'check',
            Order::STATUS_CANCELLED => 'cancelled',
            default => 'bag',
        };
    }

    private function orderTone(Order $order): string
    {
        return match ($order->status) {
            Order::STATUS_PREPARING => 'purple',
            Order::STATUS_OUT_FOR_DELIVERY => 'blue',
            Order::STATUS_DELIVERED => 'green',
            Order::STATUS_CANCELLED => 'rose',
            default => 'pink',
        };
    }

    /**
     * @param  array<int, string>  $readIds
     * @return array{id: string, title: string, message: string, time: string, icon: string, tone: string, unread: bool, occurred_at: CarbonInterface|null, url: string}
     */
    private function notification(string $id, string $title, string $message, string $icon, string $tone, ?CarbonInterface $occurredAt, string $url, array $readIds): array
    {
        return [
            'id' => $id,
            'title' => $title,
            'message' => Str::squish($message),
            'time' => $this->timeLabel($occurredAt),
            'icon' => $icon,
            'tone' => $tone,
            'unread' => ! in_array($id, $readIds, true),
            'occurred_at' => $occurredAt,
            'url' => $url,
        ];
    }

    private function timeLabel(?CarbonInterface $occurredAt): string
    {
        if (! $occurredAt instanceof CarbonInterface) {
            return 'Just now';
        }

        if ($occurredAt->isToday()) {
            return $occurredAt->diffForHumans();
        }

        if ($occurredAt->isYesterday()) {
            return 'Yesterday';
        }

        return $occurredAt->format('M j, Y');
    }

    private function money(float $amount): string
    {
        return '₱'.number_format($amount, 2);
    }
}
