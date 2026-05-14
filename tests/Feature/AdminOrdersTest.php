<?php

use App\Models\Order;
use App\Models\User;

test('admin orders page renders the order management workspace', function () {
    $customer = User::factory()->create();
    $order = Order::factory()->for($customer)->create([
        'full_name' => 'Mia Reyes',
        'email_address' => 'mia@example.com',
        'status' => Order::STATUS_PENDING,
        'created_at' => now()->setDate(2026, 5, 4)->setTime(10, 24),
    ]);
    $order->items()->create([
        'product_slug' => 'pastel-donut-box',
        'product_title' => 'Pastel Donut Box',
        'category' => 'Donuts',
        'product_image' => 'https://example.com/donut.jpg',
        'unit_price' => 120,
        'quantity' => 2,
        'line_total' => 240,
    ]);
    $order->items()->create([
        'product_slug' => 'ube-cake',
        'product_title' => 'Ube Cake',
        'category' => 'Cakes',
        'product_image' => 'https://example.com/ube.jpg',
        'unit_price' => 180,
        'quantity' => 1,
        'line_total' => 180,
    ]);
    $order->items()->create([
        'product_slug' => 'vanilla-cream-puffs',
        'product_title' => 'Vanilla Cream Puffs',
        'category' => 'Pastries',
        'product_image' => 'https://example.com/vanilla.jpg',
        'unit_price' => 80,
        'quantity' => 1,
        'line_total' => 80,
    ]);

    $this->actingAs(adminUser())
        ->get(route('admin.orders'))
        ->assertSuccessful()
        ->assertSee('Order Management')
        ->assertSee('Track, confirm, prepare, and manage customer dessert orders.')
        ->assertSee('Pending Orders')
        ->assertSee('Mark for Delivery')
        ->assertSee('Out for Delivery')
        ->assertSee('Delivered Orders')
        ->assertSee('Cancelled Orders')
        ->assertSee('Customer Dessert Orders')
        ->assertSee('New pending order')
        ->assertSee('Rows per page')
        ->assertSee('Order ID')
        ->assertSee('Customer Name')
        ->assertSee('Products')
        ->assertSee('Quantity')
        ->assertSee('Total Amount')
        ->assertSee('Date Ordered')
        ->assertSee('Status')
        ->assertSee('Actions')
        ->assertSee('Mia Reyes')
        ->assertDontSee('mia@example.com')
        ->assertSee('Pastel Donut Box')
        ->assertSee('+2 more')
        ->assertSee('May 4, 2026, 10:24 AM')
        ->assertSee('Pending')
        ->assertSee('Delivered')
        ->assertSee('Cancelled')
        ->assertSee('Approve order')
        ->assertSee('Cancel Order')
        ->assertSee('Order Details')
        ->assertSee('data-admin-details-open', false)
        ->assertSee('data-details-template="order-details-'.$order->getKey().'"', false)
        ->assertSee('Show 1 more')
        ->assertSee('data-details-extra-product', false)
        ->assertSee('data-admin-order-management', false)
        ->assertSee('data-backend-orders="true"', false)
        ->assertSee('data-order-search', false)
        ->assertSee('href="'.route('admin.orders').'" aria-current="page"', false)
        ->assertDontSee('href="'.route('admin.dashboard').'" aria-current="page"', false)
        ->assertDontSee('Print Receipt')
        ->assertDontSee('Payment method');
});

test('regular users cannot access admin routes', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.orders'))
        ->assertForbidden();
});

test('admin can update order status', function () {
    $order = Order::factory()->create();

    $this->actingAs(adminUser())
        ->patch(route('admin.orders.update', $order), [
            'status' => Order::STATUS_PREPARING,
        ])
        ->assertRedirect(route('admin.orders'));

    expect($order->refresh()->status)->toBe(Order::STATUS_PREPARING);
});
