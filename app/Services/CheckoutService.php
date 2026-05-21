<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public const DELIVERY_FEE = 0.00;

    public function __construct(
        private CartService $cart,
        private PromotionService $promotions,
    ) {}

    /**
     * @param  array{full_name: string, contact_number: string, email_address: string, complete_address: string, delivery_notes?: string|null, payment_method: string, promo_code?: string|null}  $data
     */
    public function createOrder(Request $request, array $data): Order
    {
        $user = $request->user();

        if (! $user instanceof User) {
            throw ValidationException::withMessages([
                'checkout' => 'Please log in before placing an order.',
            ]);
        }

        $cart = $this->cart->summary($request);

        if ($cart['count'] < 1) {
            throw ValidationException::withMessages([
                'cart' => 'Your cart is empty.',
            ]);
        }

        return DB::transaction(function () use ($request, $user, $data, $cart): Order {
            $deliveryFee = self::DELIVERY_FEE;
            $subtotal = (float) $cart['subtotal'];
            $promoCode = $this->promotions->normalizeCode($data['promo_code'] ?? null);
            $promotion = $this->promotions->findAvailable($promoCode);

            if ($promoCode !== null && $promotion === null) {
                throw ValidationException::withMessages([
                    'promo_code' => 'Promo code is not active or does not exist.',
                ]);
            }

            $discount = $promotion?->discountFor($subtotal) ?? 0.00;
            $total = $subtotal + $deliveryFee - $discount;

            $order = Order::query()->create([
                'order_number' => $this->makeOrderNumber(),
                'user_id' => $user->id,
                'promotion_id' => $promotion?->id,
                'promo_code' => $promotion?->code,
                'status' => Order::STATUS_PENDING,
                'full_name' => $data['full_name'],
                'contact_number' => $data['contact_number'],
                'email_address' => $data['email_address'],
                'complete_address' => $data['complete_address'],
                'delivery_notes' => $data['delivery_notes'] ?? null,
                'payment_method' => $data['payment_method'],
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'discount' => $discount,
                'total' => $total,
            ]);

            foreach ($cart['items'] as $item) {
                $product = Product::query()
                    ->where('slug', $item['slug'])
                    ->lockForUpdate()
                    ->first();

                $quantity = (int) $item['quantity'];

                if ($product instanceof Product && $product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'cart' => $product->title.' does not have enough stock.',
                    ]);
                }

                $order->items()->create([
                    'product_id' => $product?->id,
                    'product_slug' => $item['slug'],
                    'product_title' => $item['title'],
                    'category' => $item['category'],
                    'product_image' => $item['image'],
                    'unit_price' => $item['price'],
                    'quantity' => $quantity,
                    'line_total' => $item['line_total'],
                ]);

                if ($product instanceof Product) {
                    $product->forceFill([
                        'stock' => max(0, $product->stock - $quantity),
                        'sold' => $product->sold + $quantity,
                    ])->save();
                }
            }

            $this->cart->clear($request);

            return $order->load('items');
        });
    }

    private function makeOrderNumber(): string
    {
        do {
            $orderNumber = 'LBA-'.now()->format('ymd').'-'.Str::upper(Str::random(6));
        } while (Order::query()->where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}
