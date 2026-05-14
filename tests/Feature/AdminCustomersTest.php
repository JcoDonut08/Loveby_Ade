<?php

use App\Models\Order;
use App\Models\User;

test('admin customers page renders the customer management workspace', function () {
    $this->actingAs(adminUser())
        ->get(route('admin.customers'))
        ->assertSuccessful()
        ->assertSee('Customers')
        ->assertSee('Review customer profiles, purchases, spending, and activity.')
        ->assertSee('Total Customers')
        ->assertSee('Top Spenders')
        ->assertSee('Active Today')
        ->assertSee('New Customers')
        ->assertSee('Customer List')
        ->assertSee('Search customers...')
        ->assertSee('All')
        ->assertSee('Top Spender')
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
        ->assertSee('data-customer-filter="active_today"', false)
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
        ->assertDontSee('href="'.route('admin.dashboard').'" aria-current="page"', false)
        ->assertDontSee('href="'.route('admin.orders').'" aria-current="page"', false)
        ->assertDontSee('href="'.route('admin.products').'" aria-current="page"', false);
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
