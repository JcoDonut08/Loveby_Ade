<?php

use App\Models\NotificationRead;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\ProductCatalog;

test('storefront header links to customer shopping utilities', function () {
    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('href="'.route('notifications').'"', false)
        ->assertSee('href="'.route('favorites').'"', false)
        ->assertSee('href="'.route('cart').'"', false)
        ->assertSee('data-cart-nav-count', false)
        ->assertSee('data-favorite-toggle', false);
});

test('guest notifications page renders an empty state', function () {
    $this->get(route('notifications'))
        ->assertSuccessful()
        ->assertSee('Notifications')
        ->assertSee('No notifications yet')
        ->assertSee('data-notifications-empty', false)
        ->assertDontSee('data-customer-notification-row', false);
});

test('customer notifications page renders real order status updates with order ids', function () {
    $user = User::factory()->create();

    $preparingOrder = Order::factory()->for($user)->create([
        'order_number' => 'LBA-3508',
        'status' => Order::STATUS_PREPARING,
    ]);
    $deliveryOrder = Order::factory()->for($user)->create([
        'order_number' => 'LBA-3510',
        'status' => Order::STATUS_OUT_FOR_DELIVERY,
    ]);
    $cancelledOrder = Order::factory()->for($user)->create([
        'order_number' => 'LBA-3511',
        'status' => Order::STATUS_CANCELLED,
        'cancellation_reason' => 'Product unavailable',
    ]);

    $this->actingAs($user)
        ->get(route('notifications'))
        ->assertSuccessful()
        ->assertSee('Your Order LBA-3508 has been placed and is waiting for approval.')
        ->assertSee('Your Order LBA-3508 has been approved and is now being prepared.')
        ->assertSee('Your Order LBA-3510 is out for delivery.')
        ->assertSee('Your Order LBA-3511 has been cancelled. Reason: Product unavailable')
        ->assertSee('Notification center')
        ->assertSee('Rows per page')
        ->assertSee('6 rows')
        ->assertSee('Search orders and updates...')
        ->assertSee('View')
        ->assertSee('Mark all read')
        ->assertSee('data-customer-notification-row', false)
        ->assertSee('data-notification-search', false)
        ->assertSee('data-notification-row', false)
        ->assertSee('data-notification-page-size', false)
        ->assertSee('data-notification-pagination-status', false)
        ->assertSee('href="'.route('orders.receipt', $preparingOrder).'"', false)
        ->assertDontSee('No notifications yet');

    expect($preparingOrder->exists)->toBeTrue()
        ->and($deliveryOrder->exists)->toBeTrue()
        ->and($cancelledOrder->exists)->toBeTrue();
});

test('customer can mark order notifications as read', function () {
    $user = User::factory()->create();
    $order = Order::factory()->for($user)->create([
        'order_number' => 'LBA-3512',
        'status' => Order::STATUS_OUT_FOR_DELIVERY,
    ]);

    $this->actingAs($user)
        ->get(route('notifications'))
        ->assertSuccessful()
        ->assertSee('data-notification-nav-count>2</span>', false)
        ->assertSee('Mark notification as read');

    $this->actingAs($user)
        ->post(route('notifications.read-one', "order-{$order->id}-out-for-delivery"))
        ->assertRedirect(route('notifications'));

    $this->assertDatabaseHas('notification_reads', [
        'user_id' => $user->id,
        'scope' => NotificationRead::SCOPE_CUSTOMER,
        'notification_id' => "order-{$order->id}-out-for-delivery",
    ]);

    $response = $this->actingAs($user)
        ->get(route('notifications'))
        ->assertSuccessful()
        ->assertSee('Your Order LBA-3512 is out for delivery.')
        ->assertSee('data-notification-nav-count>1</span>', false);

    expect(substr_count($response->getContent(), 'border-l-4 border-love-pink-400'))->toBe(1);

    $this->post(route('logout'))->assertRedirect(route('home'));

    $response = $this->actingAs($user)
        ->get(route('notifications'))
        ->assertSuccessful()
        ->assertSee('data-notification-nav-count>1</span>', false);

    expect(substr_count($response->getContent(), 'border-l-4 border-love-pink-400'))->toBe(1);

    $this->actingAs($user)
        ->post(route('notifications.read'))
        ->assertRedirect(route('notifications'));

    $this->assertDatabaseHas('notification_reads', [
        'user_id' => $user->id,
        'scope' => NotificationRead::SCOPE_CUSTOMER,
        'notification_id' => "order-{$order->id}-placed",
    ]);

    $this->post(route('logout'))->assertRedirect(route('home'));

    $this->actingAs($user)
        ->get(route('notifications'))
        ->assertSuccessful()
        ->assertSee('Your Order LBA-3512 is out for delivery.')
        ->assertSee('hidden" data-notification-nav-count>0</span>', false)
        ->assertDontSee('border-l-4 border-love-pink-400', false);
});

test('favorites page renders saved desserts empty state and removable favorite controls', function () {
    $this->get(route('favorites'))
        ->assertSuccessful()
        ->assertSee('Favorites')
        ->assertSee('0 saved items')
        ->assertSee('No favorites yet')
        ->assertSee('data-favorites-grid', false)
        ->assertSee('data-favorites-empty', false);
});

test('cart page renders responsive cart controls subtotal and empty state without promo entry', function () {
    $this->get(route('cart'))
        ->assertSuccessful()
        ->assertSee('Shopping Cart')
        ->assertSee('Subtotal')
        ->assertSee('Proceed to checkout')
        ->assertSee('Please log in to continue checkout.')
        ->assertSee('href="'.route('login').'"', false)
        ->assertSee('href="'.route('register').'"', false)
        ->assertSee('Your cart is empty')
        ->assertSee('data-cart-page', false)
        ->assertSee('data-cart-subtotal', false)
        ->assertSee('data-cart-total', false)
        ->assertDontSee('Promo code')
        ->assertDontSee('Enter discount code if any')
        ->assertDontSee('Promo discount')
        ->assertDontSee('Shipping');
});

test('order confirmation page renders thank you message order items and totals', function () {
    $user = User::factory()->create();
    $order = Order::factory()->for($user)->create([
        'order_number' => 'LBA-515478',
    ]);
    $order->items()->create([
        'product_slug' => 'pastel-donut-box',
        'product_title' => 'Pastel Donut Box',
        'category' => 'Donuts',
        'product_image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=300&q=80',
        'unit_price' => 120,
        'quantity' => 2,
        'line_total' => 240,
    ]);

    $this->actingAs($user)
        ->withSession(['last_order_id' => $order->id])
        ->get(route('orders.confirmed'))
        ->assertSuccessful()
        ->assertSee('Payment successful')
        ->assertSee('Thanks for ordering')
        ->assertSee('images/thank-you-ordering.png')
        ->assertSee('Tracking number')
        ->assertSee('LBA-515478')
        ->assertSee('Pastel Donut Box')
        ->assertSee('Shipping')
        ->assertSee('Free')
        ->assertSee('Total')
        ->assertSee('Continue shopping')
        ->assertSee('View invoice')
        ->assertSee('href="'.route('orders.receipt', $order).'"', false);

    $this->actingAs($user)
        ->get(route('orders.receipt', $order))
        ->assertSuccessful()
        ->assertSee('Order Receipt')
        ->assertSee('RCPT-LBA-515478')
        ->assertSee('Pastel Donut Box')
        ->assertSee('Print receipt')
        ->assertSee('Download PDF')
        ->assertDontSee('Track Your Order')
        ->assertSee('href="'.route('orders.receipt', ['order' => $order, 'download' => 1]).'"', false);

    $this->actingAs($user)
        ->get(route('orders.receipt', ['order' => $order, 'download' => 1]))
        ->assertSuccessful()
        ->assertHeader('content-type', 'application/pdf')
        ->assertHeader('content-disposition', 'attachment; filename=loveby-ade-receipt-LBA-515478.pdf');
});

test('homepage recommendations fill four products from frequent purchase category', function () {
    $user = User::factory()->create();
    $products = collect([
        ['slug' => 'strawberry-cake', 'title' => 'Strawberry Cake', 'sold' => 25],
        ['slug' => 'ube-cake', 'title' => 'Ube Cake', 'sold' => 20],
        ['slug' => 'mango-cake', 'title' => 'Mango Cake', 'sold' => 15],
        ['slug' => 'vanilla-cake', 'title' => 'Vanilla Cake', 'sold' => 10],
    ])->map(fn (array $product): Product => Product::factory()->create([
        ...$product,
        'category' => 'Cakes',
        'is_active' => true,
    ]));
    Product::factory()->create([
        'slug' => 'latte',
        'title' => 'Latte',
        'category' => 'Coffees / Shakes',
        'sold' => 50,
        'is_active' => true,
    ]);
    $order = Order::factory()->for($user)->create();

    $products->take(3)->each(function (Product $product) use ($order): void {
        $order->items()->create([
            'product_id' => $product->id,
            'product_slug' => $product->slug,
            'product_title' => $product->title,
            'category' => 'Cakes',
            'unit_price' => 120,
            'quantity' => 1,
            'line_total' => 120,
        ]);
    });

    $recommendations = app(ProductCatalog::class)
        ->recommendedFor($user, 4)
        ->pluck('slug')
        ->all();

    expect($recommendations)->toHaveCount(4)
        ->and($recommendations)->toContain('vanilla-cake')
        ->and($recommendations)->toContain('strawberry-cake')
        ->and($recommendations)->not->toContain('latte');

    $this->actingAs($user)
        ->get(route('home'))
        ->assertSuccessful()
        ->assertSee('Recommended for you')
        ->assertSee('Vanilla Cake');
});
