<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WalkInOrderService
{
    /**
     * @param  array{order_number: string, customer_name: string, date_ordered: string, products: array<int, array{product_id: int|string, quantity: int|string}>}  $data
     */
    public function create(array $data, User $admin): Order
    {
        return DB::transaction(function () use ($data, $admin): Order {
            $products = Product::query()
                ->whereIn('id', collect($data['products'])->pluck('product_id'))
                ->where('is_active', true)
                ->get()
                ->keyBy('id');

            $items = collect($data['products'])
                ->map(function (array $item) use ($products): array {
                    $product = $products->get((int) $item['product_id']);
                    $quantity = (int) $item['quantity'];
                    $unitPrice = (float) $product->price;

                    return [
                        'product_id' => $product->id,
                        'product_slug' => $product->slug,
                        'product_title' => $product->title,
                        'category' => $product->category,
                        'product_image' => $this->productImage($product),
                        'unit_price' => $unitPrice,
                        'quantity' => $quantity,
                        'line_total' => $unitPrice * $quantity,
                    ];
                })
                ->values();
            $orderedAt = Carbon::parse($data['date_ordered']);
            $subtotal = $items->sum('line_total');

            $order = Order::query()->create([
                'order_number' => $data['order_number'],
                'user_id' => $admin->id,
                'status' => Order::STATUS_PENDING,
                'is_walk_in' => true,
                'full_name' => $data['customer_name'],
                'contact_number' => 'Walk-in customer',
                'email_address' => 'walk-in@lovebyade.local',
                'complete_address' => 'In-store purchase',
                'delivery_notes' => 'Walk-in order recorded by admin.',
                'payment_method' => 'Walk-in',
                'subtotal' => $subtotal,
                'delivery_fee' => 0,
                'discount' => 0,
                'total' => $subtotal,
            ]);

            $order->timestamps = false;
            $order->forceFill([
                'created_at' => $orderedAt,
                'updated_at' => $orderedAt,
            ])->saveQuietly();
            $order->timestamps = true;

            $order->items()->createMany($items->all());

            return $order;
        });
    }

    private function productImage(Product $product): ?string
    {
        if ($product->image_path) {
            return Storage::disk('public')->url($product->image_path);
        }

        return $product->image_url;
    }

    public function uniqueOrderNumber(): string
    {
        do {
            $number = 'LBA-'.random_int(100000, 999999);
        } while (Order::query()->where('order_number', $number)->exists());

        return $number;
    }
}
