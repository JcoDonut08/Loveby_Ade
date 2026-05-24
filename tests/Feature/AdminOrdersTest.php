<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\Promotion;
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
        ->assertSee('Add Order')
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
        ->assertSee('Walk-In')
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
        ->assertDontSee('href="'.route('admin.orders.receipt', $order).'"', false)
        ->assertDontSee('Print Receipt')
        ->assertDontSee('Payment method');
});

test('admin can add a walk in order with multiple products', function () {
    $cake = Product::factory()->create([
        'title' => 'Strawberry Cream Cake',
        'slug' => 'strawberry-cream-cake',
        'category' => 'Cakes',
        'price' => 840,
    ]);
    $puffs = Product::factory()->create([
        'title' => 'Vanilla Cream Puffs',
        'slug' => 'vanilla-cream-puffs',
        'category' => 'Pastries',
        'price' => 120,
    ]);

    $this->actingAs(adminUser())
        ->post(route('admin.orders.store'), [
            'order_number' => 'LBA-350999',
            'customer_name' => 'Walk In Buyer',
            'date_ordered' => '2026-05-15T14:42',
            'products' => [
                [
                    'product_id' => $cake->id,
                    'quantity' => 1,
                ],
                [
                    'product_id' => $puffs->id,
                    'quantity' => 2,
                ],
            ],
        ])
        ->assertRedirect(route('admin.orders', ['status' => 'walk_in']));

    $order = Order::query()->where('order_number', 'LBA-350999')->firstOrFail();

    expect($order->is_walk_in)->toBeTrue()
        ->and($order->full_name)->toBe('Walk In Buyer')
        ->and((float) $order->total)->toBe(1080.0)
        ->and($order->items)->toHaveCount(2);

    $this->actingAs(adminUser())
        ->get(route('admin.orders', ['status' => 'walk_in']))
        ->assertSuccessful()
        ->assertSee('Walk In Buyer')
        ->assertSee('Strawberry Cream Cake')
        ->assertSee('Mark delivered')
        ->assertSee('Cancel order')
        ->assertDontSee('aria-label="Mark for delivery"', false);
});

test('admin can add a promo code discount to a walk in order', function () {
    $cake = Product::factory()->create([
        'title' => 'Discounted Cake',
        'slug' => 'discounted-cake',
        'category' => 'Cakes',
        'price' => 500,
    ]);
    $promotion = Promotion::factory()->fixed(75)->create([
        'code' => 'WALKIN75',
    ]);

    $this->actingAs(adminUser())
        ->post(route('admin.orders.store'), [
            'order_number' => 'LBA-351111',
            'customer_name' => 'Discount Buyer',
            'date_ordered' => '2026-05-15T14:42',
            'promo_code' => 'walkin75',
            'products' => [
                [
                    'product_id' => $cake->id,
                    'quantity' => 2,
                ],
            ],
        ])
        ->assertRedirect(route('admin.orders', ['status' => 'walk_in']));

    $order = Order::query()->where('order_number', 'LBA-351111')->firstOrFail();

    expect((float) $order->subtotal)->toBe(1000.0)
        ->and($order->promotion_id)->toBe($promotion->id)
        ->and($order->promo_code)->toBe('WALKIN75')
        ->and((float) $order->discount)->toBe(75.0)
        ->and((float) $order->total)->toBe(925.0);

    $this->actingAs(adminUser())
        ->get(route('admin.orders', ['status' => 'walk_in']))
        ->assertSuccessful()
        ->assertSee('Discount Buyer')
        ->assertSee('WALKIN75')
        ->assertSee('-&#8369;75.00', false);
});

test('walk in order product ids are validated before database writes', function () {
    $this->actingAs(adminUser())
        ->post(route('admin.orders.store'), [
            'order_number' => 'LBA-351000',
            'customer_name' => "' OR 1=1 --",
            'date_ordered' => '2026-05-15T14:42',
            'products' => [
                [
                    'product_id' => "' OR 1=1 --",
                    'quantity' => 1,
                ],
            ],
        ])
        ->assertSessionHasErrors('products.0.product_id');

    expect(Order::query()->where('order_number', 'LBA-351000')->exists())->toBeFalse();
});

test('walk in orders skip delivery status transitions', function () {
    $order = Order::factory()->create([
        'is_walk_in' => true,
        'status' => Order::STATUS_PENDING,
    ]);

    $this->actingAs(adminUser())
        ->patch(route('admin.orders.update', $order), [
            'status' => Order::STATUS_PREPARING,
        ])
        ->assertSessionHasErrors('status');

    expect($order->refresh()->status)->toBe(Order::STATUS_PENDING);
});

test('regular users cannot access admin routes', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.orders'))
        ->assertForbidden();
});

test('admin can update order status', function () {
    $order = Order::factory()->create();
    $order->items()->create([
        'product_slug' => 'pastel-donut-box',
        'product_title' => 'Pastel Donut Box',
        'category' => 'Donuts',
        'product_image' => 'https://example.com/donut.jpg',
        'unit_price' => 120,
        'quantity' => 1,
        'line_total' => 120,
    ]);

    $this->actingAs(adminUser())
        ->patch(route('admin.orders.update', $order), [
            'status' => Order::STATUS_PREPARING,
        ])
        ->assertRedirect(route('admin.orders'));

    expect($order->refresh()->status)->toBe(Order::STATUS_PREPARING);

    $this->actingAs(adminUser())
        ->get(route('admin.orders'))
        ->assertSuccessful()
        ->assertSee('Print receipt')
        ->assertSee('href="'.route('admin.orders.receipt', $order).'"', false);

    $this->actingAs(adminUser())
        ->get(route('admin.orders.receipt', $order))
        ->assertSuccessful()
        ->assertSee('Order Receipt')
        ->assertSee('Pastel Donut Box')
        ->assertSee('Back to admin orders');
});

test('admin cannot mark online orders delivered', function () {
    $order = Order::factory()->create([
        'is_walk_in' => false,
        'status' => Order::STATUS_OUT_FOR_DELIVERY,
    ]);

    $this->actingAs(adminUser())
        ->patch(route('admin.orders.update', $order), [
            'status' => Order::STATUS_DELIVERED,
        ])
        ->assertSessionHasErrors('status');

    expect($order->refresh()->status)->toBe(Order::STATUS_OUT_FOR_DELIVERY);

    $this->actingAs(adminUser())
        ->get(route('admin.orders', ['status' => Order::STATUS_OUT_FOR_DELIVERY]))
        ->assertSuccessful()
        ->assertDontSee('Mark delivered');
});
