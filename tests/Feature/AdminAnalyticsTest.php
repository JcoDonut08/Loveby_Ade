<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;

test('admin analytics page renders live system data', function () {
    skipAnalyticsDatabaseTestIfNeeded($this);

    config(['app.business_timezone' => 'UTC']);
    Carbon::setTestNow(Carbon::parse('2026-05-20 12:00:00', 'UTC'));

    try {
        $admin = adminUser();
        $mia = User::factory()->create([
            'name' => 'Mia Reyes',
            'created_at' => now()->subDay(),
        ]);
        $hana = User::factory()->create([
            'name' => 'Hana Rivera',
            'created_at' => now()->subWeek()->subDay(),
        ]);
        $cake = Product::factory()->create([
            'title' => 'Ube Cloud Cake',
            'slug' => 'ube-cloud-cake',
            'category' => 'Cakes',
            'stock' => 7,
            'sold' => 0,
            'price' => 180,
        ]);
        $cookies = Product::factory()->create([
            'title' => 'Chocolate Chip Cookies',
            'slug' => 'chocolate-chip-cookies',
            'category' => 'Cookies',
            'stock' => 9,
            'sold' => 0,
            'price' => 80,
        ]);

        createAnalyticsOrder($mia, $cake, [
            'order_number' => 'LBA-520001',
            'status' => Order::STATUS_PENDING,
            'payment_method' => 'GCash',
            'quantity' => 2,
            'line_total' => 360,
            'total' => 500,
            'created_at' => now()->subDay(),
        ]);
        createAnalyticsOrder($hana, $cookies, [
            'order_number' => 'LBA-520002',
            'status' => Order::STATUS_DELIVERED,
            'payment_method' => 'Cash on Delivery',
            'quantity' => 3,
            'line_total' => 240,
            'total' => 240,
            'created_at' => now()->subHours(2),
        ]);
        createAnalyticsOrder($mia, $cake, [
            'order_number' => 'LBA-520003',
            'status' => Order::STATUS_PENDING,
            'payment_method' => 'Cash on Delivery',
            'quantity' => 1,
            'line_total' => 180,
            'total' => 180,
            'created_at' => now()->subHours(3),
        ]);
        createAnalyticsOrder($hana, $cake, [
            'order_number' => 'LBA-519001',
            'status' => Order::STATUS_DELIVERED,
            'payment_method' => 'Cash on Delivery',
            'quantity' => 1,
            'line_total' => 180,
            'total' => 180,
            'created_at' => now()->subWeek(),
        ]);
        createAnalyticsOrder($mia, $cookies, [
            'order_number' => 'LBA-520004',
            'status' => Order::STATUS_CANCELLED,
            'payment_method' => 'GCash',
            'quantity' => 9,
            'line_total' => 720,
            'total' => 720,
            'created_at' => now()->subHour(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.analytics'))
            ->assertSuccessful()
            ->assertSee('Analytics')
            ->assertSee('Date range')
            ->assertSee('Today')
            ->assertSee('This Week')
            ->assertSee('This Month')
            ->assertSee('This Year')
            ->assertSee('Total Revenue')
            ->assertSee('&#8369;740', false)
            ->assertSee('Total Orders')
            ->assertSee('3')
            ->assertSee('1 completed + 2 active')
            ->assertSee('Total Customers')
            ->assertSee('2')
            ->assertSee('1 new customers this period')
            ->assertSee('Best-Selling Product')
            ->assertSee('Chocolate Chip Cookies')
            ->assertSee('3 sold items')
            ->assertSee('Sales report')
            ->assertSee('Mia Reyes')
            ->assertSee('Ube Cloud Cake')
            ->assertSee('&#8369;360.00', false)
            ->assertSee('Product performance')
            ->assertSee('9 left')
            ->assertSee('25%')
            ->assertSee('Showing 1-3 of 3 sales')
            ->assertSee('Showing 1-2 of 2 desserts')
            ->assertSee('data-admin-analytics', false)
            ->assertSee('data-analytics-table', false)
            ->assertSee('data-analytics-row', false)
            ->assertSee('data-analytics-page-size', false)
            ->assertSee('data-analytics-page-buttons', false)
            ->assertSee('href="'.route('admin.analytics', ['period' => 'week']).'" aria-current="true"', false)
            ->assertSee('href="'.route('admin.analytics').'" aria-current="page"', false)
            ->assertDontSee('Pastel Donut Box')
            ->assertDontSee('LBA-520004');
    } finally {
        Carbon::setTestNow();
    }
});

test('admin analytics filters reports by date range and search term', function () {
    skipAnalyticsDatabaseTestIfNeeded($this);

    config(['app.business_timezone' => 'UTC']);
    Carbon::setTestNow(Carbon::parse('2026-05-20 12:00:00', 'UTC'));

    try {
        $admin = adminUser();
        $customer = User::factory()->create(['name' => 'Luna Santos']);
        $todayProduct = Product::factory()->create([
            'title' => 'Today Tart',
            'slug' => 'today-tart',
            'category' => 'Pastries',
            'stock' => 5,
        ]);
        $oldProduct = Product::factory()->create([
            'title' => 'Old Cake',
            'slug' => 'old-cake',
            'category' => 'Cakes',
            'stock' => 5,
        ]);

        createAnalyticsOrder($customer, $todayProduct, [
            'order_number' => 'LBA-530001',
            'status' => Order::STATUS_DELIVERED,
            'payment_method' => 'Cash on Delivery',
            'quantity' => 2,
            'line_total' => 220,
            'total' => 220,
            'created_at' => now(),
        ]);
        createAnalyticsOrder($customer, $oldProduct, [
            'order_number' => 'LBA-529001',
            'status' => Order::STATUS_DELIVERED,
            'payment_method' => 'Cash on Delivery',
            'quantity' => 4,
            'line_total' => 400,
            'total' => 400,
            'created_at' => now()->subDay(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.analytics', [
                'period' => 'today',
                'search' => 'Today',
            ]))
            ->assertSuccessful()
            ->assertSee('value="Today"', false)
            ->assertSee('Today Tart')
            ->assertSee('&#8369;220', false)
            ->assertSee('Showing 1-1 of 1 sales')
            ->assertSee('Showing 1-1 of 1 desserts')
            ->assertSee('href="'.route('admin.analytics', ['period' => 'today', 'search' => 'Today']).'" aria-current="true"', false)
            ->assertDontSee('Old Cake')
            ->assertDontSee('LBA-529001');
    } finally {
        Carbon::setTestNow();
    }
});

/**
 * @param  array{order_number: string, status: string, payment_method: string, quantity: int, line_total: int|float, total: int|float, created_at: Carbon}  $data
 */
function createAnalyticsOrder(User $customer, Product $product, array $data): Order
{
    $order = Order::factory()
        ->for($customer)
        ->create([
            'order_number' => $data['order_number'],
            'status' => $data['status'],
            'full_name' => $customer->name,
            'email_address' => $customer->email,
            'payment_method' => $data['payment_method'],
            'subtotal' => $data['total'],
            'delivery_fee' => 0,
            'discount' => 0,
            'total' => $data['total'],
            'created_at' => $data['created_at'],
            'updated_at' => $data['created_at'],
        ]);

    OrderItem::factory()
        ->for($order)
        ->for($product)
        ->create([
            'product_slug' => $product->slug,
            'product_title' => $product->title,
            'category' => $product->category,
            'unit_price' => $product->price,
            'quantity' => $data['quantity'],
            'line_total' => $data['line_total'],
            'created_at' => $data['created_at'],
            'updated_at' => $data['created_at'],
        ]);

    return $order;
}

function skipAnalyticsDatabaseTestIfNeeded(TestCase $testCase): void
{
    if (config('database.default') === 'sqlite' && ! extension_loaded('pdo_sqlite')) {
        $testCase->markTestSkipped('PDO SQLite is required for in-memory feature database tests.');
    }
}
