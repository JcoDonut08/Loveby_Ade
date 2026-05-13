<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::query()
            ->whereBelongsTo($request->user())
            ->with('items')
            ->latest()
            ->get();

        return view('pages.orders.index', [
            'orders' => $orders->map(fn (Order $order): array => $this->orderCard($order)),
        ]);
    }

    public function confirmed(Request $request): View
    {
        $order = null;
        $lastOrderId = $request->session()->get('last_order_id');

        if ($lastOrderId && $request->user()) {
            $order = Order::query()
                ->whereBelongsTo($request->user())
                ->with('items')
                ->find($lastOrderId);
        }

        return view('pages.orders.confirmed', [
            'order' => $order,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function orderCard(Order $order): array
    {
        $featuredItem = $order->items->first();
        $statusMeta = $this->statusMeta($order->status);

        return [
            'id' => 'Order #'.$order->order_number,
            'status' => $order->status,
            'status_label' => $statusMeta['label'],
            'status_badge' => $statusMeta['badge'],
            'placed_at' => $order->created_at?->format('F j, Y') ?? '',
            'featured_name' => $featuredItem?->product_title ?? 'Dessert order',
            'featured_image' => $featuredItem?->product_image ?? 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=320&q=80',
            'total' => '&#8369;'.number_format((float) $order->total, 2),
            'quantity' => $order->items->sum('quantity'),
            'description' => $order->items->pluck('product_title')->join(', '),
            'recipient' => $order->full_name,
            'delivery_lines' => [$order->complete_address],
            'update_email' => $order->email_address,
            'update_phone' => $order->contact_number,
            'current_step' => $statusMeta['step'],
            'cancelled_copy' => $order->status === Order::STATUS_CANCELLED
                ? 'Cancelled reason: '.($order->cancellation_reason ?: 'No reason provided.')
                : '',
        ];
    }

    /**
     * @return array{label: string, badge: string, step: int}
     */
    private function statusMeta(string $status): array
    {
        return match ($status) {
            Order::STATUS_PREPARING => [
                'label' => 'Mark for Delivery',
                'badge' => 'bg-love-pink-100 text-love-pink-500 ring-love-pink-200',
                'step' => 2,
            ],
            Order::STATUS_OUT_FOR_DELIVERY => [
                'label' => 'Out for Delivery',
                'badge' => 'bg-love-blue-100 text-[#23445c] ring-love-blue-200',
                'step' => 3,
            ],
            Order::STATUS_DELIVERED => [
                'label' => 'Delivered',
                'badge' => 'bg-emerald-100 text-emerald-600 ring-emerald-200',
                'step' => 4,
            ],
            Order::STATUS_CANCELLED => [
                'label' => 'Cancelled',
                'badge' => 'bg-rose-100 text-rose-500 ring-rose-200',
                'step' => 0,
            ],
            default => [
                'label' => 'Pending',
                'badge' => 'bg-amber-100 text-amber-700 ring-amber-200',
                'step' => 1,
            ],
        };
    }
}
