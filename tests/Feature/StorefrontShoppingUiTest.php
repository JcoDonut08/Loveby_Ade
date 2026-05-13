<?php

use App\Models\Order;
use App\Models\User;

test('storefront header links to customer shopping utilities', function () {
    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('href="'.route('notifications').'"', false)
        ->assertSee('href="'.route('favorites').'"', false)
        ->assertSee('href="'.route('cart').'"', false)
        ->assertSee('data-cart-nav-count', false)
        ->assertSee('data-favorite-toggle', false);
});

test('customer notifications page renders order and promo updates with an empty state', function () {
    $this->get(route('notifications'))
        ->assertSuccessful()
        ->assertSee('Notifications')
        ->assertSee('Your order is now being prepared.')
        ->assertSee('Payment confirmed via GCash.')
        ->assertSee('New promo: 10% off on cakes today.')
        ->assertSee('Your delivery is out for delivery.')
        ->assertSee('Mark all read')
        ->assertSee('No notifications yet')
        ->assertSee('data-customer-notification-row', false)
        ->assertSee('data-notifications-empty', false);
});

test('favorites page renders saved desserts empty state and removable favorite controls', function () {
    $this->get(route('favorites'))
        ->assertSuccessful()
        ->assertSee('Favorites')
        ->assertSee('0 saved items')
        ->assertSee('No favorites yet')
        ->assertSee('data-favorites-grid', false)
        ->assertSee('data-favorites-empty', false);
});

test('cart page renders responsive cart controls subtotal promo code and empty state', function () {
    $this->get(route('cart'))
        ->assertSuccessful()
        ->assertSee('Shopping Cart')
        ->assertSee('Enter discount code if any')
        ->assertSee('Subtotal')
        ->assertSee('Proceed to checkout')
        ->assertSee('Please log in to continue checkout.')
        ->assertSee('href="'.route('login').'"', false)
        ->assertSee('href="'.route('register').'"', false)
        ->assertSee('Your cart is empty')
        ->assertSee('data-cart-page', false)
        ->assertSee('data-cart-subtotal', false)
        ->assertSee('data-cart-total', false)
        ->assertDontSee('Shipping');
});

test('order confirmation page renders thank you message order items and totals', function () {
    $user = User::factory()->create();
    $order = Order::factory()->for($user)->create([
        'order_number' => 'LBA-515478',
    ]);
    $order->items()->create([
        'product_slug' => 'pastel-donut-box',
        'product_title' => 'Pastel Donut Box',
        'category' => 'Donuts',
        'product_image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=300&q=80',
        'unit_price' => 120,
        'quantity' => 2,
        'line_total' => 240,
    ]);

    $this->actingAs($user)
        ->withSession(['last_order_id' => $order->id])
        ->get(route('orders.confirmed'))
        ->assertSuccessful()
        ->assertSee('Payment successful')
        ->assertSee('Thanks for ordering')
        ->assertSee('Tracking number')
        ->assertSee('LBA-515478')
        ->assertSee('Pastel Donut Box')
        ->assertSee('Total')
        ->assertSee('Continue shopping');
});
