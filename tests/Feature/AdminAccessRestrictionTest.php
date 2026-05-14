<?php

use App\Models\User;

test('an admin is redirected away from storefront pages', function (string $routeName, mixed $parameters = []) {
    $this->actingAs(adminUser())
        ->get(route($routeName, $parameters))
        ->assertRedirect(route('admin.dashboard'));
})->with([
    'homepage' => ['home'],
    'products' => ['products.index'],
    'product details' => ['products.show-by-slug', 'chocolate-chip-cookies'],
    'customer account' => ['account'],
    'cart' => ['cart'],
]);

test('customers can still access storefront pages', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('home'))
        ->assertSuccessful();
});
