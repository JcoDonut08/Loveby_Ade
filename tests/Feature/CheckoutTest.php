<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\User;

test('checkout page renders shipping payment review and confirmation steps', function () {
    $this->postJson(route('cart.items.store'), [
        'slug' => 'pastel-donut-box',
        'quantity' => 2,
    ])->assertSuccessful();

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
        ->assertSee('Delivery fee')
        ->assertSee('Free')
        ->assertSee('Place Order')
        ->assertSee('Order has been placed')
        ->assertSee('data-confirm-url="'.route('orders.confirm').'"', false)
        ->assertSee('data-checkout-page', false)
        ->assertDontSee('data-checkout-progress-fill', false)
        ->assertSee('data-payment-card', false);
});

test('orders confirm alias renders the confirmation page', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('orders.confirm'))
        ->assertRedirect(route('orders.confirmed'));
});

test('authenticated customer can place an order from the cart', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('cart.items.store'), [
            'slug' => 'pastel-donut-box',
            'quantity' => 2,
        ])
        ->assertSuccessful();

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

test('checkout keeps the exact cart quantity up to available stock', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create([
        'slug' => 'celebration-cookie-box',
        'title' => 'Celebration Cookie Box',
        'stock' => 42,
        'price' => 75,
    ]);

    $this->actingAs($user)
        ->postJson(route('cart.items.store'), [
            'slug' => 'celebration-cookie-box',
            'quantity' => 42,
        ])
        ->assertSuccessful()
        ->assertJsonPath('items.0.quantity', 42);

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
