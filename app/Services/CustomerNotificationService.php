<?php

namespace App\Services;

use App\Models\NotificationRead;
use App\Models\Order;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class CustomerNotificationService
{
    /**
     * @param  array<int, string>  $readIds
     * @return Collection<int, array{id: string, title: string, message: string, time: string, icon: string, tone: string, unread: bool, url: string, occurred_at: CarbonInterface|null}>
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
     * @return array<int, string>
     */
    public function readIdsFor(?User $user): array
    {
        if (! $user instanceof User) {
            return [];
        }

        return $user->notificationReads()
            ->where('scope', NotificationRead::SCOPE_CUSTOMER)
            ->pluck('notification_id')
            ->all();
    }

    public function markReadFor(User $user, string $notificationId): void
    {
        $user->notificationReads()->firstOrCreate([
            'scope' => NotificationRead::SCOPE_CUSTOMER,
            'notification_id' => $notificationId,
        ]);
    }

    public function markAllReadFor(User $user): void
    {
        $now = now();
        $notifications = collect($this->notificationIdsFor($user))
            ->map(fn (string $notificationId): array => [
                'user_id' => $user->id,
                'scope' => NotificationRead::SCOPE_CUSTOMER,
                'notification_id' => $notificationId,
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->all();

        if ($notifications === []) {
            return;
        }

        NotificationRead::query()->upsert(
            $notifications,
            ['user_id', 'scope', 'notification_id'],
            ['updated_at'],
        );
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

    public function unreadCountForUser(?User $user): int
    {
        return $this->unreadCountFor($user, $this->readIdsFor($user));
    }

    /**
     * @param  array<int, string>  $readIds
     * @return array<int, array{id: string, title: string, message: string, time: string, icon: string, tone: string, unread: bool, url: string, occurred_at: CarbonInterface|null}>
     */
    private function notificationsForOrder(Order $order, array $readIds): array
    {
        $orderNumber = $order->order_number;
        $orderUrl = route('orders.receipt', $order);
        $notifications = [
            $this->notification(
                id: "order-{$order->id}-placed",
                title: 'Order placed',
                message: "Your Order {$orderNumber} has been placed and is waiting for approval.",
                icon: 'bag',
                tone: 'orange',
                occurredAt: $order->created_at,
                readIds: $readIds,
                url: $orderUrl,
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
                url: $orderUrl,
            ),
            Order::STATUS_OUT_FOR_DELIVERY => $this->notification(
                id: "order-{$order->id}-out-for-delivery",
                title: 'Delivery update',
                message: "Your Order {$orderNumber} is out for delivery.",
                icon: 'delivery',
                tone: 'blue',
                occurredAt: $order->updated_at,
                readIds: $readIds,
                url: $orderUrl,
            ),
            Order::STATUS_DELIVERED => $this->notification(
                id: "order-{$order->id}-delivered",
                title: 'Order delivered',
                message: "Your Order {$orderNumber} has been delivered. Thank you for ordering from Loveby_Ade.",
                icon: 'check',
                tone: 'green',
                occurredAt: $order->updated_at,
                readIds: $readIds,
                url: $orderUrl,
            ),
            Order::STATUS_CANCELLED => $this->notification(
                id: "order-{$order->id}-cancelled",
                title: 'Order cancelled',
                message: trim("Your Order {$orderNumber} has been cancelled. ".($order->cancellation_reason ? 'Reason: '.$order->cancellation_reason : '')),
                icon: 'cancelled',
                tone: 'rose',
                occurredAt: $order->updated_at,
                readIds: $readIds,
                url: $orderUrl,
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
     * @return array{id: string, title: string, message: string, time: string, icon: string, tone: string, unread: bool, url: string, occurred_at: CarbonInterface|null}
     */
    private function notification(string $id, string $title, string $message, string $icon, string $tone, ?CarbonInterface $occurredAt, array $readIds, string $url): array
    {
        return [
            'id' => $id,
            'title' => $title,
            'message' => $message,
            'time' => $this->timeLabel($occurredAt),
            'icon' => $icon,
            'tone' => $tone,
            'unread' => ! in_array($id, $readIds, true),
            'url' => $url,
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
