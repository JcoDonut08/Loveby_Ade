<?php

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('a guest can add products to the session cart', function () {
    $this->postJson(route('cart.items.store'), [
        'slug' => 'pastel-donut-box',
        'quantity' => 2,
    ])
        ->assertSuccessful()
        ->assertJsonPath('count', 2)
        ->assertJsonPath('items.0.title', 'Pastel Donut Box');

    $this->get(route('cart'))
        ->assertSuccessful()
        ->assertSee('Pastel Donut Box')
        ->assertSee('2 items')
        ->assertSee('data-cart-slug="pastel-donut-box"', false)
        ->assertSee('Please log in to continue checkout.');
});

test('cart items stay after the guest logs in', function () {
    $user = User::factory()->create([
        'email' => 'cart@example.com',
        'password' => 'secret-password',
    ]);

    $this->postJson(route('cart.items.store'), [
        'slug' => 'chocolate-chip-cookies',
        'quantity' => 1,
    ])->assertSuccessful();

    $this->post(route('login.store'), [
        'email' => 'cart@example.com',
        'password' => 'secret-password',
    ])->assertRedirect(route('home'));

    $this->assertAuthenticatedAs($user);

    $this->get(route('cart'))
        ->assertSuccessful()
        ->assertSee('Chocolate Chip Cookies')
        ->assertSee('Proceed to checkout')
        ->assertSee('href="'.route('checkout').'"', false)
        ->assertSee('data-cart-checkout-link', false)
        ->assertDontSee('Please log in to continue checkout.');

    $this->assertModelExists(CartItem::query()->whereBelongsTo($user)->where('product_slug', 'chocolate-chip-cookies')->firstOrFail());
});

test('cart quantities can use the available product stock above twenty', function () {
    Product::factory()->create([
        'slug' => 'party-cookie-tray',
        'title' => 'Party Cookie Tray',
        'stock' => 42,
        'price' => 50,
    ]);

    $this->postJson(route('cart.items.store'), [
        'slug' => 'party-cookie-tray',
        'quantity' => 42,
    ])
        ->assertSuccessful()
        ->assertJsonPath('count', 42)
        ->assertJsonPath('items.0.quantity', 42);

    $this->patchJson(route('cart.items.update', 'party-cookie-tray'), [
        'quantity' => 37,
    ])
        ->assertSuccessful()
        ->assertJsonPath('count', 37)
        ->assertJsonPath('items.0.quantity', 37);
});

test('products with no stock cannot be added to the cart', function () {
    Product::factory()->create([
        'slug' => 'sold-out-cookie-box',
        'title' => 'Sold Out Cookie Box',
        'stock' => 0,
        'price' => 95,
    ]);

    $this->postJson(route('cart.items.store'), [
        'slug' => 'sold-out-cookie-box',
        'quantity' => 1,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('slug');

    $this->getJson(route('cart.summary'))
        ->assertSuccessful()
        ->assertJsonPath('count', 0)
        ->assertJsonCount(0, 'items');
});

test('authenticated cart items stay after logout and logging back in', function () {
    $user = User::factory()->create([
        'email' => 'returning-cart@example.com',
        'password' => 'secret-password',
    ]);

    $this->actingAs($user);

    $this->postJson(route('cart.items.store'), [
        'slug' => 'pastel-donut-box',
        'quantity' => 1,
    ])->assertSuccessful();

    $this->post(route('logout'))->assertRedirect(route('home'));

    $this->assertGuest();

    $this->post(route('login.store'), [
        'email' => 'returning-cart@example.com',
        'password' => 'secret-password',
    ])->assertRedirect(route('home'));

    $this->assertAuthenticatedAs($user);

    $this->get(route('cart'))
        ->assertSuccessful()
        ->assertSee('Pastel Donut Box')
        ->assertSee('1 item')
        ->assertSee('href="'.route('checkout').'"', false);
});

test('cart quantities can be updated and removed from the session', function () {
    $this->postJson(route('cart.items.store'), [
        'slug' => 'mini-cake-cups',
        'quantity' => 1,
    ])->assertSuccessful();

    $this->patchJson(route('cart.items.update', 'mini-cake-cups'), [
        'quantity' => 3,
    ])
        ->assertSuccessful()
        ->assertJsonPath('count', 3);

    $this->deleteJson(route('cart.items.destroy', 'mini-cake-cups'))
        ->assertSuccessful()
        ->assertJsonPath('count', 0)
        ->assertJsonCount(0, 'items');
});
