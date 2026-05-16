<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;

test('admin dashboard renders live overview data', function () {
    $admin = adminUser();
    $mia = User::factory()->create([
        'name' => 'Mia Reyes',
        'last_active_at' => now(),
    ]);
    $hana = User::factory()->create([
        'name' => 'Hana Rivera',
    ]);
    $cake = Product::factory()->create([
        'title' => 'Strawberry Cream Cake',
        'slug' => 'strawberry-cream-cake',
        'category' => 'Cakes',
        'stock' => 4,
        'price' => 840,
    ]);
    $donut = Product::factory()->create([
        'title' => 'Pastel Donut Box',
        'slug' => 'pastel-donut-box',
        'category' => 'Donuts',
        'stock' => 18,
        'price' => 330,
    ]);
    $pendingOrder = Order::factory()
        ->for($mia)
        ->create([
            'order_number' => 'LBA-3508',
            'status' => Order::STATUS_PENDING,
            'full_name' => 'Mia Reyes',
            'payment_method' => 'Cash on Delivery',
            'subtotal' => 840,
            'delivery_fee' => 0,
            'total' => 840,
            'created_at' => now()->subMinutes(2),
        ]);
    OrderItem::factory()
        ->for($pendingOrder)
        ->for($cake)
        ->create([
            'product_slug' => 'strawberry-cream-cake',
            'product_title' => 'Strawberry Cream Cake',
            'category' => 'Cakes',
            'unit_price' => 840,
            'quantity' => 1,
            'line_total' => 840,
            'created_at' => now()->subMinutes(2),
        ]);
    $deliveredOrder = Order::factory()
        ->for($hana)
        ->create([
            'order_number' => 'LBA-3507',
            'status' => Order::STATUS_DELIVERED,
            'full_name' => 'Hana Rivera',
            'payment_method' => 'Cash on Delivery',
            'subtotal' => 330,
            'delivery_fee' => 0,
            'total' => 330,
            'created_at' => now()->subHour(),
        ]);
    OrderItem::factory()
        ->for($deliveredOrder)
        ->for($donut)
        ->create([
            'product_slug' => 'pastel-donut-box',
            'product_title' => 'Pastel Donut Box',
            'category' => 'Donuts',
            'unit_price' => 330,
            'quantity' => 1,
            'line_total' => 330,
            'created_at' => now()->subHour(),
        ]);
    $prepaidOrder = Order::factory()
        ->for($mia)
        ->create([
            'order_number' => 'LBA-3506',
            'status' => Order::STATUS_PENDING,
            'full_name' => 'Mia Reyes',
            'payment_method' => 'GCash',
            'subtotal' => 500,
            'delivery_fee' => 0,
            'total' => 500,
            'created_at' => now()->subMinutes(10),
        ]);
    OrderItem::factory()
        ->for($prepaidOrder)
        ->for($donut)
        ->create([
            'product_slug' => 'pastel-donut-box',
            'product_title' => 'Pastel Donut Box',
            'category' => 'Donuts',
            'unit_price' => 500,
            'quantity' => 1,
            'line_total' => 500,
            'created_at' => now()->subMinutes(10),
        ]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertSuccessful()
        ->assertSee('Dashboard')
        ->assertSee('Revenue')
        ->assertSee('₱830')
        ->assertSee('Avg. order')
        ->assertSee('₱415.0')
        ->assertSee('1 product needs restocking')
        ->assertSee('Strawberry Cream Cake')
        ->assertSee('LBA-3508')
        ->assertSee('Mia Reyes')
        ->assertSee('Pastel Donut Box')
        ->assertSee('href="'.route('admin.products').'"', false)
        ->assertSee('href="'.route('admin.dashboard').'" aria-current="page"', false)
        ->assertDontSee('href="'.route('admin.orders').'" aria-current="page"', false)
        ->assertSee('data-admin-todo', false)
        ->assertSee('max-h-80', false)
        ->assertSee('overflow-y-auto', false)
        ->assertSee('data-sales-performance', false)
        ->assertDontSee('₱48,290')
        ->assertDontSee('Track payments, fulfillment, and customer updates');
});

test('restock alert uses healthy styling when every product has enough stock', function () {
    Product::factory()->create([
        'title' => 'Chocolate Cupcake Box',
        'stock' => 18,
    ]);

    $this->actingAs(adminUser())
        ->get(route('admin.dashboard'))
        ->assertSuccessful()
        ->assertSee('0 products need restocking')
        ->assertSee('All products are stocked right now')
        ->assertSee('Stock levels healthy')
        ->assertSee('bg-emerald-50', false);
});
