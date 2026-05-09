<?php

test('storefront header links to customer shopping utilities', function () {
    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('href="'.route('notifications').'"', false)
        ->assertSee('href="'.route('favorites').'"', false)
        ->assertSee('href="'.route('cart').'"', false)
        ->assertSee('data-cart-nav-count', false)
        ->assertSee('data-favorite-toggle', false);
});

test('customer notifications page renders order and promo updates with an empty state', function () {
    $this->get(route('notifications'))
        ->assertSuccessful()
        ->assertSee('Notifications')
        ->assertSee('Your order is now being prepared.')
        ->assertSee('Payment confirmed via GCash.')
        ->assertSee('New promo: 10% off on cakes today.')
        ->assertSee('Your delivery is out for delivery.')
        ->assertSee('Mark all read')
        ->assertSee('No notifications yet')
        ->assertSee('data-customer-notification-row', false)
        ->assertSee('data-notifications-empty', false);
});

test('favorites page renders saved desserts and removable favorite controls', function () {
    $this->get(route('favorites'))
        ->assertSuccessful()
        ->assertSee('Favorites')
        ->assertSee('Pastel Donut Box')
        ->assertSee('Mini Cake Cups')
        ->assertSee('Chocolate Chip Cookies')
        ->assertSee('Add to Cart')
        ->assertSee('Remove')
        ->assertSee('No favorites yet')
        ->assertSee('data-favorite-toggle', false)
        ->assertSee('data-favorite-remove', false)
        ->assertSee('data-favorites-empty', false);
});

test('cart page renders responsive cart controls subtotal promo code and empty state', function () {
    $this->get(route('cart'))
        ->assertSuccessful()
        ->assertSee('Shopping Cart')
        ->assertSee('Pastel Donut Box')
        ->assertSee('Chocolate Chip Cookies')
        ->assertSee('Mini Cake Cups')
        ->assertSee('Enter discount code if any')
        ->assertSee('Subtotal')
        ->assertSee('Checkout')
        ->assertSee('Your cart is empty')
        ->assertSee('data-cart-page', false)
        ->assertSee('data-cart-item', false)
        ->assertSee('data-cart-subtotal', false)
        ->assertSee('data-cart-total', false)
        ->assertDontSee('Shipping');
});
