<?php

use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\User;

/**
 * @return array<string, array<string, int>>
 */
function checkoutGuestCart(string $slug = 'pastel-donut-box', int $quantity = 1): array
{
    return [
        'cart.items' => [
            $slug => $quantity,
        ],
    ];
}

function checkoutDatabaseCart(User $user, string $slug = 'pastel-donut-box', int $quantity = 1): void
{
    CartItem::query()->create([
        'user_id' => $user->id,
        'product_slug' => $slug,
        'quantity' => $quantity,
    ]);
}

test('checkout page renders shipping payment review and confirmation steps', function () {
    $this->withSession(checkoutGuestCart(quantity: 2));

    $this->get(route('checkout'))
        ->assertSuccessful()
        ->assertSee('Shipping Details')
        ->assertSee('Payment Method')
        ->assertSee('Order Review')
        ->assertSee('Confirmation')
        ->assertSee('Full name')
        ->assertSee('Contact number')
        ->assertSee('+63-', false)
        ->assertSee('placeholder="0000000000"', false)
        ->assertSee('name="contact_number_digits"', false)
        ->assertSee('Email address')
        ->assertSee('Complete address')
        ->assertSee('Delivery notes')
        ->assertSee('Continue to Payment')
        ->assertSee('GCash')
        ->assertSee('PayMaya')
        ->assertSee('Cash on Delivery')
        ->assertSee('Continue to Review')
        ->assertSee('Pastel Donut Box')
        ->assertSee('Promo code')
        ->assertSee('data-checkout-step="3"', false)
        ->assertSee('name="checkout_step" value="3"', false)
        ->assertSee('data-checkout-promo-form', false)
        ->assertSee('data-promo-url="'.route('checkout.promo').'"', false)
        ->assertSee('Delivery fee')
        ->assertSee('Free')
        ->assertSee('Place Order')
        ->assertSee('Order has been placed')
        ->assertSee('data-confirm-url="'.route('orders.confirm').'"', false)
        ->assertSee('data-checkout-page', false)
        ->assertDontSee('data-checkout-progress-fill', false)
        ->assertSee('data-payment-card', false);
});

test('checkout opens on review step after applying a promo code', function () {
    $promotion = Promotion::factory()->fixed(25)->create([
        'code' => 'STAY25',
    ]);

    $this->withSession(checkoutGuestCart());

    $this->get(route('checkout', [
        'promo_code' => $promotion->code,
        'checkout_step' => 3,
    ]))
        ->assertSuccessful()
        ->assertSee('data-initial-step="3"', false)
        ->assertSee('STAY25 applied.');
});

test('checkout promo preview applies active discounts without changing payment method', function () {
    $promotion = Promotion::factory()->fixed(25)->create([
        'code' => 'STAY25',
    ]);

    $this->withSession(checkoutGuestCart());

    $this->getJson(route('checkout.promo', ['promo_code' => ' stay25 ']))
        ->assertSuccessful()
        ->assertJsonPath('applied.code', 'STAY25')
        ->assertJsonPath('error', null)
        ->assertJsonPath('formattedDiscount', "\u{20B1}25.00");

    $this->get(route('checkout', [
        'promo_code' => $promotion->code,
        'checkout_step' => 3,
        'payment_method' => 'Cash on Delivery',
    ]))
        ->assertSuccessful()
        ->assertSee('value="Cash on Delivery"', false)
        ->assertSee('data-payment-title="Cash on Delivery"', false)
        ->assertSee('aria-pressed="true"', false);
});

test('invalid promo preview does not attach a checkout promo code', function () {
    $this->withSession(checkoutGuestCart());

    $this->getJson(route('checkout.promo', ['promo_code' => 'NOPE']))
        ->assertSuccessful()
        ->assertJsonPath('applied', null)
        ->assertJsonPath('error', 'Promo code is not active or does not exist.')
        ->assertJsonPath('formattedDiscount', "\u{20B1}0.00");
});

test('orders confirm alias renders the confirmation page', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('orders.confirm'))
        ->assertRedirect(route('orders.confirmed'));
});

test('authenticated customer can place an order from the cart', function () {
    $user = User::factory()->create();

    checkoutDatabaseCart($user, quantity: 2);

    $this->actingAs($user)
        ->post(route('checkout.store'), [
            'full_name' => 'Ade Santos',
            'contact_number_digits' => '9171234567',
            'email_address' => 'ade@example.com',
            'complete_address' => '123 Bakery Lane',
            'delivery_notes' => 'Ring the bell.',
            'payment_method' => 'Cash on Delivery',
        ])
        ->assertRedirect(route('orders.confirmed'));

    $order = Order::query()->whereBelongsTo($user)->with('items')->firstOrFail();

    expect($order->status)->toBe(Order::STATUS_PENDING)
        ->and($order->contact_number)->toBe('+63-9171234567')
        ->and((float) $order->delivery_fee)->toBe(0.0)
        ->and((float) $order->total)->toBe((float) $order->subtotal)
        ->and($order->items)->toHaveCount(1)
        ->and($order->items->first()->product_title)->toBe('Pastel Donut Box');
});

test('authenticated customer can apply an active promo code at checkout', function () {
    $user = User::factory()->create();
    $promotion = Promotion::factory()->fixed(50)->create([
        'code' => 'SWEET50',
    ]);

    checkoutDatabaseCart($user, quantity: 2);

    $this->actingAs($user)
        ->get(route('checkout', ['promo_code' => 'sweet50']))
        ->assertSuccessful()
        ->assertSee('SWEET50 applied.');

    $this->actingAs($user)
        ->post(route('checkout.store'), [
            'full_name' => 'Ade Santos',
            'contact_number_digits' => '9171234567',
            'email_address' => 'ade@example.com',
            'complete_address' => '123 Bakery Lane',
            'delivery_notes' => 'Ring the bell.',
            'payment_method' => 'Cash on Delivery',
            'promo_code' => 'sweet50',
        ])
        ->assertRedirect(route('orders.confirmed'));

    $order = Order::query()->whereBelongsTo($user)->firstOrFail();

    expect($order->promotion_id)->toBe($promotion->id)
        ->and($order->promo_code)->toBe('SWEET50')
        ->and($order->payment_method)->toBe('Cash on Delivery')
        ->and((float) $order->discount)->toBe(50.0)
        ->and((float) $order->total)->toBe((float) $order->subtotal - 50.0);
});

test('checkout keeps the exact cart quantity up to available stock', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create([
        'slug' => 'celebration-cookie-box',
        'title' => 'Celebration Cookie Box',
        'stock' => 42,
        'price' => 75,
    ]);

    checkoutDatabaseCart($user, slug: 'celebration-cookie-box', quantity: 42);

    $this->actingAs($user)
        ->post(route('checkout.store'), [
            'full_name' => 'Ade Santos',
            'contact_number_digits' => '9171234567',
            'email_address' => 'ade@example.com',
            'complete_address' => '123 Bakery Lane',
            'delivery_notes' => 'Ring the bell.',
            'payment_method' => 'Cash on Delivery',
        ])
        ->assertRedirect(route('orders.confirmed'));

    $order = Order::query()->whereBelongsTo($user)->with('items')->firstOrFail();

    expect($order->items->first()->quantity)->toBe(42)
        ->and($product->fresh()->stock)->toBe(0);
});

test('checkout rejects phone numbers outside the fixed philippine format', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('checkout.store'), [
            'full_name' => 'Ade Santos',
            'contact_number_digits' => '917123',
            'email_address' => 'ade@example.com',
            'complete_address' => '123 Bakery Lane',
            'delivery_notes' => 'Ring the bell.',
            'payment_method' => 'Cash on Delivery',
        ])
        ->assertSessionHasErrors('contact_number_digits');
});
