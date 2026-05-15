<?php

use App\Models\Order;
use App\Models\User;
use App\Services\AdminCustomerDirectory;
use Illuminate\Support\Facades\DB;

test('admin customers page renders the customer management workspace', function () {
    $this->actingAs(adminUser())
        ->get(route('admin.customers'))
        ->assertSuccessful()
        ->assertSee('Customers')
        ->assertSee('Review customer profiles, purchases, spending, and activity.')
        ->assertSee('Total Customers')
        ->assertSee('Top Spenders')
        ->assertSee('Regular Customers')
        ->assertSee('New Customers')
        ->assertSee('Customer List')
        ->assertSee('Search customers...')
        ->assertSee('All')
        ->assertSee('Top Spender')
        ->assertSee('Regular Customer')
        ->assertSee('New Customer')
        ->assertSee('Customer list pagination', false)
        ->assertSee('Customers per page')
        ->assertSee('3 customers')
        ->assertSee('5 customers')
        ->assertSee('10 customers')
        ->assertSee('Previous')
        ->assertSee('Next')
        ->assertSee('Customer Profile')
        ->assertSee('data-admin-customers', false)
        ->assertSee('data-customer-global-search', false)
        ->assertSee('data-customer-search', false)
        ->assertSee('data-customer-filter="all"', false)
        ->assertSee('data-customer-filter="top_spender"', false)
        ->assertSee('data-customer-filter="regular_customer"', false)
        ->assertSee('data-customer-list', false)
        ->assertSee('data-customer-summary-count="total"', false)
        ->assertSee('data-customer-result-count', false)
        ->assertSee('data-customer-page-size', false)
        ->assertSee('data-customer-pagination-status', false)
        ->assertSee('data-customer-page-buttons', false)
        ->assertSee('data-customer-details', false)
        ->assertSee('data-customer-details-title', false)
        ->assertSee('data-customer-details-content', false)
        ->assertSee('href="'.route('admin.customers').'" aria-current="page"', false)
        ->assertDontSee('Top Fan')
        ->assertDontSee('data-customer-filter="top_fan"', false)
        ->assertDontSee('data-customer-filter="active_today"', false)
        ->assertDontSee('href="'.route('admin.dashboard').'" aria-current="page"', false)
        ->assertDontSee('href="'.route('admin.orders').'" aria-current="page"', false)
        ->assertDontSee('href="'.route('admin.products').'" aria-current="page"', false);
});

test('admin customer segments are exclusive and add up to all customers', function () {
    $topSpender = User::factory()->create(['created_at' => now()->subMonth()]);
    Order::factory()->for($topSpender)->create([
        'status' => Order::STATUS_DELIVERED,
        'total' => 1000,
    ]);

    $regularCustomer = User::factory()->create(['created_at' => now()->subMonth()]);
    Order::factory()->for($regularCustomer)->create([
        'status' => Order::STATUS_DELIVERED,
        'total' => 250,
    ]);

    $pendingCustomer = User::factory()->create(['created_at' => now()->subMonth()]);
    Order::factory()->for($pendingCustomer)->create([
        'status' => Order::STATUS_PENDING,
        'total' => 1500,
    ]);

    $customers = collect(app(AdminCustomerDirectory::class)->customers());

    expect($customers)->toHaveCount(3)
        ->and($customers->where('segment', 'top_spender'))->toHaveCount(1)
        ->and($customers->where('segment', 'regular_customer'))->toHaveCount(1)
        ->and($customers->where('segment', 'new_customer'))->toHaveCount(1);
});

test('admin customer spending metadata only counts delivered orders', function () {
    $customer = User::factory()->create([
        'email' => 'pending-buyer@example.com',
    ]);
    Order::factory()->for($customer)->create([
        'order_number' => 'LBA-PENDING',
        'status' => Order::STATUS_PENDING,
        'total' => 1500,
    ]);
    Order::factory()->for($customer)->create([
        'order_number' => 'LBA-DELIVERED',
        'status' => Order::STATUS_DELIVERED,
        'total' => 250,
    ]);

    $payload = collect(app(AdminCustomerDirectory::class)->customers())
        ->firstWhere('email', 'pending-buyer@example.com');

    $orders = collect($payload['orders']);

    expect($payload['segment'])->toBe('regular_customer')
        ->and($orders->contains(fn (array $order): bool => $order['id'] === 'LBA-PENDING' && $order['isDelivered'] === false))->toBeTrue()
        ->and($orders->contains(fn (array $order): bool => $order['id'] === 'LBA-DELIVERED' && $order['isDelivered'] === true))->toBeTrue();
});

test('admin customer last active reflects active sessions and logout activity', function () {
    $activeCustomer = User::factory()->create([
        'last_active_at' => now()->subHour(),
    ]);
    DB::table('sessions')->insert([
        'id' => 'active-customer-session',
        'user_id' => $activeCustomer->id,
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Pest',
        'payload' => '',
        'last_activity' => now()->timestamp,
    ]);

    $loggedOutCustomer = User::factory()->create([
        'last_active_at' => now()->subMinutes(15),
    ]);

    $customers = collect(app(AdminCustomerDirectory::class)->customers())->keyBy('email');

    expect($customers[$activeCustomer->email]['lastActive'])->toBe('Active now')
        ->and($customers[$loggedOutCustomer->email]['lastActive'])->not->toBe('Active now')
        ->and($customers[$loggedOutCustomer->email]['lastActive'])->toContain('ago');
});

test('admin customers page uses real customer and order data', function () {
    $customer = User::factory()->create([
        'name' => 'Mia Reyes',
        'email' => 'mia.reyes@example.com',
        'contact_number' => '+63-9172841930',
        'created_at' => now()->subMonth(),
    ]);
    $order = Order::factory()->for($customer)->create([
        'order_number' => 'LBA-3508',
        'status' => Order::STATUS_DELIVERED,
        'total' => 1170,
        'created_at' => now()->subDay(),
    ]);
    $order->items()->create([
        'product_slug' => 'strawberry-cream-cake',
        'product_title' => 'Strawberry Cream Cake',
        'category' => 'Cakes',
        'unit_price' => 840,
        'quantity' => 1,
        'line_total' => 840,
    ]);

    $this->actingAs(adminUser())
        ->get(route('admin.customers'))
        ->assertSuccessful()
        ->assertSee('Mia Reyes')
        ->assertSee('mia.reyes@example.com')
        ->assertSee('LBA-3508')
        ->assertSee('Strawberry Cream Cake')
        ->assertSee('data-customers', false)
        ->assertDontSee('Mika Santos')
        ->assertDontSee('mock customers shown');
});
