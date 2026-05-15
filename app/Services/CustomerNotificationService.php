<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class CustomerNotificationService
{
    /**
     * @param  array<int, string>  $readIds
     * @return Collection<int, array{id: string, title: string, message: string, time: string, icon: string, tone: string, unread: bool, occurred_at: CarbonInterface|null}>
     */
    public function notificationsFor(?User $user, array $readIds = []): Collection
    {
        if (! $user instanceof User) {
            return collect();
        }

        return Order::query()
            ->whereBelongsTo($user)
            ->latest('updated_at')
            ->get()
            ->flatMap(fn (Order $order): array => $this->notificationsForOrder($order, $readIds))
            ->sortByDesc('occurred_at')
            ->values();
    }

    /**
     * @return array<int, string>
     */
    public function notificationIdsFor(?User $user): array
    {
        return $this->notificationsFor($user)
            ->pluck('id')
            ->all();
    }

    /**
     * @param  array<int, string>  $readIds
     */
    public function unreadCountFor(?User $user, array $readIds = []): int
    {
        return $this->notificationsFor($user, $readIds)
            ->where('unread', true)
            ->count();
    }

    /**
     * @param  array<int, string>  $readIds
     * @return array<int, array{id: string, title: string, message: string, time: string, icon: string, tone: string, unread: bool, occurred_at: CarbonInterface|null}>
     */
    private function notificationsForOrder(Order $order, array $readIds): array
    {
        $orderNumber = $order->order_number;
        $notifications = [
            $this->notification(
                id: "order-{$order->id}-placed",
                title: 'Order placed',
                message: "Your Order {$orderNumber} has been placed and is waiting for approval.",
                icon: 'bag',
                tone: 'orange',
                occurredAt: $order->created_at,
                readIds: $readIds,
            ),
        ];

        $statusNotification = match ($order->status) {
            Order::STATUS_PREPARING => $this->notification(
                id: "order-{$order->id}-approved",
                title: 'Order approved',
                message: "Your Order {$orderNumber} has been approved and is now being prepared.",
                icon: 'prep',
                tone: 'pink',
                occurredAt: $order->updated_at,
                readIds: $readIds,
            ),
            Order::STATUS_OUT_FOR_DELIVERY => $this->notification(
                id: "order-{$order->id}-out-for-delivery",
                title: 'Delivery update',
                message: "Your Order {$orderNumber} is out for delivery.",
                icon: 'delivery',
                tone: 'blue',
                occurredAt: $order->updated_at,
                readIds: $readIds,
            ),
            Order::STATUS_DELIVERED => $this->notification(
                id: "order-{$order->id}-delivered",
                title: 'Order delivered',
                message: "Your Order {$orderNumber} has been delivered. Thank you for ordering from Loveby_Ade.",
                icon: 'check',
                tone: 'green',
                occurredAt: $order->updated_at,
                readIds: $readIds,
            ),
            Order::STATUS_CANCELLED => $this->notification(
                id: "order-{$order->id}-cancelled",
                title: 'Order cancelled',
                message: trim("Your Order {$orderNumber} has been cancelled. ".($order->cancellation_reason ? 'Reason: '.$order->cancellation_reason : '')),
                icon: 'cancelled',
                tone: 'rose',
                occurredAt: $order->updated_at,
                readIds: $readIds,
            ),
            default => null,
        };

        if ($statusNotification !== null) {
            $notifications[] = $statusNotification;
        }

        return $notifications;
    }

    /**
     * @param  array<int, string>  $readIds
     * @return array{id: string, title: string, message: string, time: string, icon: string, tone: string, unread: bool, occurred_at: CarbonInterface|null}
     */
    private function notification(string $id, string $title, string $message, string $icon, string $tone, ?CarbonInterface $occurredAt, array $readIds): array
    {
        return [
            'id' => $id,
            'title' => $title,
            'message' => $message,
            'time' => $this->timeLabel($occurredAt),
            'icon' => $icon,
            'tone' => $tone,
            'unread' => ! in_array($id, $readIds, true),
            'occurred_at' => $occurredAt,
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
}
