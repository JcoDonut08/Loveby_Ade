<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;

test('admin notifications page renders live notification data and controls', function () {
    $customer = User::factory()->create(['name' => 'Sophia Laurent']);
    $order = Order::factory()
        ->for($customer)
        ->create([
            'order_number' => 'LBA-3421',
            'full_name' => 'Sophia Laurent',
            'status' => Order::STATUS_PENDING,
            'total' => 84.50,
            'updated_at' => now()->subMinutes(2),
        ]);
    $product = Product::factory()->create([
        'title' => 'Glazed Vanilla Donuts',
        'stock' => 8,
        'updated_at' => now()->subMinutes(12),
    ]);
    $reviewedProduct = Product::factory()->create([
        'title' => 'Chocolate Dream Cake',
        'stock' => 25,
    ]);

    ProductReview::factory()
        ->for($reviewedProduct, 'product')
        ->for($customer, 'user')
        ->create([
            'rating' => 5,
            'created_at' => now()->subHour(),
        ]);

    $this->actingAs(adminUser())
        ->get(route('admin.notifications'))
        ->assertSuccessful()
        ->assertSee('Notifications')
        ->assertSee('Everything happening across your shop.')
        ->assertSee('Notification center')
        ->assertSee('4 unread - 4 total')
        ->assertSee('New order received')
        ->assertSee($order->order_number.' from Sophia Laurent - ₱84.50')
        ->assertSee('Low stock alert')
        ->assertSee($product->title.' only 8 units left')
        ->assertSee('New customer registered')
        ->assertSee('Sophia Laurent joined Loveby_Ade')
        ->assertSee('Review received')
        ->assertSee('Chocolate Dream Cake earned 5 stars')
        ->assertSee('Rows per page')
        ->assertSee('6 rows')
        ->assertSee('9 rows')
        ->assertSee('12 rows')
        ->assertSee('Showing 1-4 of 4 notifications')
        ->assertSee('Mark all read')
        ->assertSee('Mark notification as read')
        ->assertSee('href="'.e(route('admin.orders', ['status' => Order::STATUS_PENDING, 'search' => $order->order_number])).'"', false)
        ->assertSee('href="'.route('admin.products', ['search' => $product->title]).'"', false)
        ->assertSee('data-admin-notifications', false)
        ->assertSee('data-notification-search', false)
        ->assertSee('data-notification-row', false)
        ->assertSee('data-notification-page-size', false)
        ->assertSee('data-notification-pagination-status', false)
        ->assertSee('data-notification-page-buttons', false)
        ->assertSee('href="'.route('admin.notifications').'" aria-current="page"', false);
});

test('admin can mark one notification as read', function () {
    $admin = adminUser();
    $order = Order::factory()
        ->for($admin)
        ->create([
            'order_number' => 'LBA-7001',
            'full_name' => 'Mina Cruz',
            'status' => Order::STATUS_PENDING,
        ]);

    $this->actingAs($admin)
        ->get(route('admin.notifications'))
        ->assertSuccessful()
        ->assertSee('1 unread - 1 total')
        ->assertSee('Mark notification as read');

    $this->post(route('admin.notifications.read-one', "admin-order-{$order->id}-pending"))
        ->assertRedirect(route('admin.notifications'));

    $this->get(route('admin.notifications'))
        ->assertSuccessful()
        ->assertSee('0 unread - 1 total')
        ->assertDontSee('Mark notification as read');
});

test('admin can mark all notifications as read', function () {
    $admin = adminUser();

    Order::factory()
        ->for($admin)
        ->create([
            'order_number' => 'LBA-8001',
            'full_name' => 'Rhea Santos',
            'status' => Order::STATUS_PENDING,
        ]);
    Product::factory()->create([
        'title' => 'Mini Berry Tarts',
        'stock' => 3,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.notifications'))
        ->assertSuccessful()
        ->assertSee('2 unread - 2 total');

    $this->post(route('admin.notifications.read'))
        ->assertRedirect(route('admin.notifications'));

    $this->get(route('admin.notifications'))
        ->assertSuccessful()
        ->assertSee('0 unread - 2 total')
        ->assertSee('Notifications marked as read.')
        ->assertDontSee('Mark notification as read');
});
