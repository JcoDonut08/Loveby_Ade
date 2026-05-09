<?php

test('product index renders filters and product cards', function () {
    $this->get(route('products.index'))
        ->assertSuccessful()
        ->assertDontSee('Dessert catalog')
        ->assertSee('Search')
        ->assertSee('Category')
        ->assertSee('Min price')
        ->assertSee('Max price')
        ->assertSee('Pastel Donut Box')
        ->assertSee('Milk Tea Cookie Tin')
        ->assertSee('href="'.route('products.show-by-slug', 'milk-tea-cookie-tin').'"', false);
});

test('product index can filter by search category and price', function () {
    $this->get(route('products.index', [
        'search' => 'cookie',
        'category' => 'Cookies',
        'min_price' => 80,
        'max_price' => 95,
    ]))
        ->assertSuccessful()
        ->assertSee('Chocolate Chip Cookies')
        ->assertSee('Milk Tea Cookie Tin')
        ->assertDontSee('Pastel Donut Box');
});

test('dynamic product overview renders selected product details', function () {
    $this->get(route('products.show-by-slug', 'chocolate-chip-cookies'))
        ->assertSuccessful()
        ->assertSee('Chocolate Chip Cookies')
        ->assertSee('Golden cookies with soft centers')
        ->assertSee('226 sold')
        ->assertSee('You may also like');
});
