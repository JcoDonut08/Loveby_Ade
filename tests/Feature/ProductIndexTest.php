<?php

use App\Services\SearchAssistant;
use Illuminate\Http\Request;

test('product index renders filters and product cards', function () {
    $this->get(route('products.index'))
        ->assertSuccessful()
        ->assertDontSee('Dessert catalog')
        ->assertSee('Search')
        ->assertSee('Category')
        ->assertSee('Min price')
        ->assertSee('Max price')
        ->assertSee('Coffees / Shakes')
        ->assertSee('data-auto-filter-form', false)
        ->assertSee('data-product-search-preview-form', false)
        ->assertSee('data-product-results-grid', false)
        ->assertSee('data-product-search-text', false)
        ->assertSee('data-product-search-empty', false)
        ->assertDontSee('Apply')
        ->assertDontSee('Brownies')
        ->assertDontSee('Donuts')
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

    expect(session('store.recent_searches'))->toBe(['cookie']);
});

test('search suggestions hide recent searches while typing and include accurate product recommendations', function () {
    $this->withSession([
        'store.recent_searches' => ['cake cups', 'cookie'],
    ])
        ->getJson(route('search.suggestions', ['q' => 'cookie']))
        ->assertSuccessful()
        ->assertJsonPath('recent', [])
        ->assertJsonFragment([
            'title' => 'Chocolate Chip Cookies',
            'subtitle' => 'Cookies - PHP 90.00',
            'url' => route('products.index', ['search' => 'Chocolate Chip Cookies']),
        ])
        ->assertJsonFragment([
            'title' => 'Milk Tea Cookie Tin',
        ]);
});

test('search suggestions show recent searches before typing', function () {
    $this->withSession([
        'store.recent_searches' => ['cake cups', 'cookie'],
    ])
        ->getJson(route('search.suggestions'))
        ->assertSuccessful()
        ->assertJsonPath('recent.0.title', 'cake cups')
        ->assertJsonPath('recent.1.title', 'cookie')
        ->assertJsonPath('suggestions', []);
});

test('recent searches can be removed from the session', function () {
    $this->withSession([
        'store.recent_searches' => ['cake cups', 'cookie'],
    ])
        ->deleteJson(route('search.recent.destroy'), ['term' => 'cake cups'])
        ->assertSuccessful()
        ->assertJsonPath('recent.0.title', 'cookie');

    expect(session('store.recent_searches'))->toBe(['cookie']);
});

test('search assistant stores recent searches in the session', function () {
    $request = Request::create('/products', 'GET');
    $request->setLaravelSession(app('session.store'));

    app(SearchAssistant::class)->remember($request, ' cookie ');

    expect(app(SearchAssistant::class)->recent($request))
        ->toBe([
            [
                'title' => 'cookie',
                'url' => route('products.index', ['search' => 'cookie']),
            ],
        ]);
});

test('store header search includes youtube style autocomplete hooks', function () {
    $view = file_get_contents(resource_path('views/components/home/store-header.blade.php'));

    expect($view)
        ->toContain('data-search-autocomplete-form')
        ->toContain("route('search.suggestions')")
        ->toContain("route('search.recent.destroy')")
        ->toContain('Recent searches')
        ->toContain('Search recommendations');
});

test('storefront brand mark uses the uploaded logo image', function () {
    expect(public_path('images/lovebyadelogo.png'))->toBeFile();

    $this->get(route('products.index'))
        ->assertSuccessful()
        ->assertSee('rel="icon" type="image/png"', false)
        ->assertSee('images/lovebyadelogo.png', false)
        ->assertSee('alt="Loveby_Ade logo"', false);
});

test('admin sidebar uses the uploaded logo image', function () {
    $view = file_get_contents(resource_path('views/components/admin/sidebar.blade.php'));

    expect($view)
        ->toContain("asset('images/lovebyadelogo.png')")
        ->toContain('alt="Loveby_Ade logo"');
});

test('admin layout uses the uploaded logo image as the page icon', function () {
    $view = file_get_contents(resource_path('views/layouts/admin.blade.php'));

    expect($view)
        ->toContain('rel="icon" type="image/png"')
        ->toContain("asset('images/lovebyadelogo.png')");
});

test('product index can filter by the homepage coffees and shakes category', function () {
    $this->get(route('products.index', [
        'category' => 'Coffees / Shakes',
    ]))
        ->assertSuccessful()
        ->assertSee('Strawberry Cream Shake')
        ->assertSee('Iced Caramel Latte')
        ->assertDontSee('Pastel Donut Box');
});

test('dynamic product overview renders selected product details', function () {
    $this->get(route('products.show-by-slug', 'chocolate-chip-cookies'))
        ->assertSuccessful()
        ->assertSee('Chocolate Chip Cookies')
        ->assertSee('Golden cookies with soft centers')
        ->assertSee('226 sold')
        ->assertSee('aspect-[3/2]', false)
        ->assertDontSee('min-h-[32rem]', false)
        ->assertSee('You may also like');
});
