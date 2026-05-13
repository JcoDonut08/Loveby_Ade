<?php

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('authenticated users can view customer order cards with admin aligned statuses', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('orders.index'))
        ->assertSuccessful()
        ->assertSee('Order #LBA-3508')
        ->assertSee('View invoice ->', false)
        ->assertSee('Delivery address')
        ->assertSee('Shipping updates')
        ->assertSee('Quantity: 3')
        ->assertSee('Pending')
        ->assertSee('Mark for Delivery')
        ->assertSee('Out for Delivery')
        ->assertSee('Delivered')
        ->assertSee('Cancelled reason: Duplicate order.')
        ->assertSee('data-customer-orders', false)
        ->assertSee('data-customer-order-card', false)
        ->assertSee('data-order-progress-segment', false)
        ->assertDontSee('Shipping address')
        ->assertDontSee('Preparing to ship');
});
