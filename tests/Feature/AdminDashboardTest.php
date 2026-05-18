<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\AdminDashboardOverview;
use Illuminate\Support\Carbon;

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
        ->assertSee('data-sales-total-value', false)
        ->assertSee('data-sales-period-total', false)
        ->assertDontSee('₱48,290')
        ->assertDontSee('Track payments, fulfillment, and customer updates');
});

test('admin dashboard sales charts bucket paid orders by the current date ranges', function () {
    config(['app.business_timezone' => 'UTC']);
    Carbon::setTestNow(Carbon::parse('2026-05-18 12:00:00'));

    try {
        $customer = User::factory()->create();

        Order::factory()->for($customer)->create([
            'status' => Order::STATUS_DELIVERED,
            'payment_method' => 'Cash on Delivery',
            'total' => 150,
            'created_at' => now()->setTime(10, 15),
        ]);
        Order::factory()->for($customer)->create([
            'status' => Order::STATUS_PENDING,
            'payment_method' => 'GCash',
            'total' => 250,
            'created_at' => now()->setTime(14, 30),
        ]);
        Order::factory()->for($customer)->create([
            'status' => Order::STATUS_CANCELLED,
            'payment_method' => 'GCash',
            'total' => 999,
            'created_at' => now()->setTime(14, 45),
        ]);

        $salesPerformance = app(AdminDashboardOverview::class)->data()['salesPerformance'];

        $dailyBars = collect($salesPerformance['daily']['bars'])->keyBy('label');
        $weeklyBars = collect($salesPerformance['weekly']['bars'])->keyBy('label');
        $monthlyBars = collect($salesPerformance['monthly']['bars'])->keyBy('label');
        $yearlyBars = collect($salesPerformance['yearly']['bars'])->keyBy('label');

        expect($dailyBars['10 AM']['amount'])->toContain('150')
            ->and($dailyBars['2 PM']['amount'])->toContain('250')
            ->and($weeklyBars['Mon']['amount'])->toContain('400')
            ->and($weeklyBars['Tue']['amount'])->toContain('0')
            ->and($monthlyBars['Week 3']['amount'])->toContain('400')
            ->and($yearlyBars['Q2']['amount'])->toContain('400');
    } finally {
        Carbon::setTestNow();
    }
});

test('admin dashboard weekly chart uses the business timezone for late-night orders', function () {
    config(['app.business_timezone' => 'Asia/Manila']);
    Carbon::setTestNow(Carbon::parse('2026-05-18 19:30:00', 'UTC'));

    try {
        $customer = User::factory()->create();

        Order::factory()->for($customer)->create([
            'status' => Order::STATUS_PENDING,
            'payment_method' => 'GCash',
            'total' => 450,
            'created_at' => Carbon::parse('2026-05-18 19:24:00', 'UTC'),
        ]);

        $weeklyBars = collect(app(AdminDashboardOverview::class)->data()['salesPerformance']['weekly']['bars'])
            ->keyBy('label');

        expect($weeklyBars['Mon']['amount'])->toContain('0')
            ->and($weeklyBars['Tue']['amount'])->toContain('450');
    } finally {
        Carbon::setTestNow();
    }
});

test('admin dashboard top desserts uses the order date for this week', function () {
    config(['app.business_timezone' => 'UTC']);
    Carbon::setTestNow(Carbon::parse('2026-05-18 12:00:00'));

    try {
        $customer = User::factory()->create();
        $oldCake = Product::factory()->create([
            'title' => 'Old Cake',
            'category' => 'Cakes',
        ]);
        $freshCookie = Product::factory()->create([
            'title' => 'Fresh Cookie',
            'category' => 'Cookies',
        ]);

        $oldOrder = Order::factory()->for($customer)->create([
            'status' => Order::STATUS_DELIVERED,
            'created_at' => now()->subDays(10),
        ]);
        OrderItem::factory()->for($oldOrder)->for($oldCake)->create([
            'category' => 'Cakes',
            'quantity' => 9,
            'created_at' => now(),
        ]);

        $recentOrder = Order::factory()->for($customer)->create([
            'status' => Order::STATUS_DELIVERED,
            'created_at' => now()->subDays(2),
        ]);
        OrderItem::factory()->for($recentOrder)->for($freshCookie)->create([
            'category' => 'Cookies',
            'quantity' => 2,
            'created_at' => now()->subDays(2),
        ]);

        $labels = collect(app(AdminDashboardOverview::class)->data()['topDesserts'])
            ->pluck('label')
            ->all();

        expect($labels)
            ->toContain('Cookies')
            ->not->toContain('Cakes');
    } finally {
        Carbon::setTestNow();
    }
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
