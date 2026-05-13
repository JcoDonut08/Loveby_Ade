<?php

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
        ->assertSee('Place Order')
        ->assertSee('Order has been placed')
        ->assertSee('data-confirm-url="'.route('orders.confirm').'"', false)
        ->assertSee('data-checkout-page', false)
        ->assertDontSee('data-checkout-progress-fill', false)
        ->assertSee('data-payment-card', false);
});

test('orders confirm alias renders the confirmation page', function () {
    $this->get(route('orders.confirm'))
        ->assertSuccessful()
        ->assertSee('Thanks for ordering')
        ->assertSee('Tracking number');
});
