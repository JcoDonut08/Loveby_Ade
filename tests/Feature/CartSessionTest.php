<?php

use App\Models\CartItem;
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
        ->assertSee('href="'.route('orders.confirmed').'"', false)
        ->assertDontSee('Please log in to continue checkout.');

    $this->assertModelExists(CartItem::query()->whereBelongsTo($user)->where('product_slug', 'chocolate-chip-cookies')->firstOrFail());
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
        ->assertSee('href="'.route('orders.confirmed').'"', false);
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
