<?php

use App\Models\FavoriteItem;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('a guest can save products to session favorites', function () {
    $this->postJson(route('favorites.items.store'), [
        'slug' => 'pastel-donut-box',
    ])
        ->assertSuccessful()
        ->assertJsonPath('count', 1)
        ->assertJsonPath('favorited', true)
        ->assertJsonPath('items.0.title', 'Pastel Donut Box');

    $this->get(route('favorites'))
        ->assertSuccessful()
        ->assertSee('Pastel Donut Box')
        ->assertSee('1 saved item')
        ->assertSee('data-favorite-card', false)
        ->assertSee('data-product-slug="pastel-donut-box"', false);
});

test('guest favorites merge into the user after login', function () {
    $user = User::factory()->create([
        'email' => 'favorites@example.com',
        'password' => 'secret-password',
    ]);

    $this->postJson(route('favorites.items.store'), [
        'slug' => 'chocolate-chip-cookies',
    ])->assertSuccessful();

    $this->post(route('login.store'), [
        'email' => 'favorites@example.com',
        'password' => 'secret-password',
    ])->assertRedirect(route('home'));

    $this->assertAuthenticatedAs($user);

    $this->get(route('favorites'))
        ->assertSuccessful()
        ->assertSee('Chocolate Chip Cookies')
        ->assertSee('1 saved item');

    $this->assertModelExists(FavoriteItem::query()->whereBelongsTo($user)->where('product_slug', 'chocolate-chip-cookies')->firstOrFail());
});

test('authenticated favorites stay after logout and logging back in', function () {
    $user = User::factory()->create([
        'email' => 'returning-favorites@example.com',
        'password' => 'secret-password',
    ]);

    $this->actingAs($user);

    $this->postJson(route('favorites.items.store'), [
        'slug' => 'mini-cake-cups',
    ])->assertSuccessful();

    $this->post(route('logout'))->assertRedirect(route('home'));

    $this->assertGuest();

    $this->post(route('login.store'), [
        'email' => 'returning-favorites@example.com',
        'password' => 'secret-password',
    ])->assertRedirect(route('home'));

    $this->assertAuthenticatedAs($user);

    $this->get(route('favorites'))
        ->assertSuccessful()
        ->assertSee('Mini Cake Cups')
        ->assertSee('1 saved item');
});

test('favorites can be toggled off and removed', function () {
    $this->postJson(route('favorites.items.store'), [
        'slug' => 'berry-danish-set',
    ])->assertSuccessful();

    $this->postJson(route('favorites.items.store'), [
        'slug' => 'berry-danish-set',
    ])
        ->assertSuccessful()
        ->assertJsonPath('count', 0)
        ->assertJsonPath('favorited', false)
        ->assertJsonCount(0, 'items');

    $this->postJson(route('favorites.items.store'), [
        'slug' => 'berry-danish-set',
    ])->assertSuccessful();

    $this->deleteJson(route('favorites.items.destroy', 'berry-danish-set'))
        ->assertSuccessful()
        ->assertJsonPath('count', 0)
        ->assertJsonPath('favorited', false)
        ->assertJsonCount(0, 'items');
});
