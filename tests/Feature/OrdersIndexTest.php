<?php

use App\Models\Order;
use App\Models\User;

test('authenticated users can view customer order cards with admin aligned statuses', function () {
    $user = User::factory()->create();
    $order = Order::factory()->for($user)->create([
        'order_number' => 'LBA-3508',
        'full_name' => 'Mia Reyes',
        'complete_address' => '24 Sampaguita Lane, Makati City',
        'status' => Order::STATUS_PENDING,
    ]);
    $order->items()->create([
        'product_slug' => 'pastel-donut-box',
        'product_title' => 'Pastel Donut Box',
        'category' => 'Donuts',
        'product_image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=320&q=80',
        'unit_price' => 120,
        'quantity' => 3,
        'line_total' => 360,
    ]);

    $this->actingAs($user)
        ->get(route('orders.index'))
        ->assertSuccessful()
        ->assertSee('Order #LBA-3508')
        ->assertSee('View invoice ->', false)
        ->assertSee('href="'.route('orders.receipt', $order).'"', false)
        ->assertSee('Confirm Order')
        ->assertSee('disabled', false)
        ->assertSee('Delivery address')
        ->assertSee('Shipping updates')
        ->assertSee('Quantity: 3')
        ->assertSee('Pending')
        ->assertSee('Mark for Delivery')
        ->assertSee('Out for Delivery')
        ->assertSee('Delivered')
        ->assertSee('data-customer-orders', false)
        ->assertSee('data-customer-order-card', false)
        ->assertSee('data-order-progress-segment', false)
        ->assertDontSee('Shipping address')
        ->assertDontSee('Preparing to ship');
});

test('customer cannot view another customers receipt', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $order = Order::factory()->for($owner)->create();

    $this->actingAs($otherUser)
        ->get(route('orders.receipt', $order))
        ->assertNotFound();
});

test('customer can confirm an out for delivery order as delivered', function () {
    $user = User::factory()->create();
    $order = Order::factory()->for($user)->create([
        'order_number' => 'LBA-3510',
        'status' => Order::STATUS_OUT_FOR_DELIVERY,
    ]);
    $order->items()->create([
        'product_slug' => 'pastel-donut-box',
        'product_title' => 'Pastel Donut Box',
        'category' => 'Donuts',
        'product_image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=320&q=80',
        'unit_price' => 120,
        'quantity' => 1,
        'line_total' => 120,
    ]);

    $this->actingAs($user)
        ->patch(route('orders.confirm-delivery', $order))
        ->assertRedirect(route('orders.index'));

    expect($order->refresh()->status)->toBe(Order::STATUS_DELIVERED);

    $this->actingAs($user)
        ->get(route('orders.index'))
        ->assertSuccessful()
        ->assertDontSee('Order #LBA-3510');

    $this->actingAs($user)
        ->get(route('delivered-products.index'))
        ->assertSuccessful()
        ->assertSee('Order #LBA-3510')
        ->assertSee('Pastel Donut Box')
        ->assertDontSee('View invoice ->', false)
        ->assertDontSee('Edit')
        ->assertDontSee('data-order-progress-segment', false);
});

test('customer cannot confirm an order before it is out for delivery', function () {
    $user = User::factory()->create();
    $order = Order::factory()->for($user)->create([
        'status' => Order::STATUS_PREPARING,
    ]);

    $this->actingAs($user)
        ->from(route('orders.index'))
        ->patch(route('orders.confirm-delivery', $order))
        ->assertRedirect(route('orders.index'))
        ->assertSessionHasErrors('order');

    expect($order->refresh()->status)->toBe(Order::STATUS_PREPARING);
});
