<?php

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

test('product overview page renders the storefront sections', function () {
    $this->get(route('products.show'))
        ->assertSuccessful()
        ->assertSee('Pastel Donut Box')
        ->assertSee('Add to cart')
        ->assertSee('Product Ratings')
        ->assertSee('Write a review')
        ->assertSee('You may also like')
        ->assertSee('data-review-rating', false)
        ->assertDontSee('Frontend preview only')
        ->assertDontSee('Verified');
});

test('product detail page renders uploaded gallery images', function () {
    Storage::fake('public');

    Storage::disk('public')->put('products/gallery-primary.jpg', 'image');
    Storage::disk('public')->put('products/gallery-side.jpg', 'image');

    Product::factory()->create([
        'slug' => 'gallery-cake',
        'title' => 'Gallery Cake',
        'image_path' => 'products/gallery-primary.jpg',
        'product_images' => [
            'products/gallery-primary.jpg',
            'products/gallery-side.jpg',
        ],
        'image_url' => null,
    ]);

    $this->get(route('products.show-by-slug', 'gallery-cake'))
        ->assertSuccessful()
        ->assertSee('Gallery Cake')
        ->assertSee('data-product-main-image', false)
        ->assertSee('data-product-thumb', false)
        ->assertSee('products/gallery-primary.jpg', false)
        ->assertSee('products/gallery-side.jpg', false);
});

test('default pastel donut route reflects uploaded database gallery images', function () {
    Storage::fake('public');

    Storage::disk('public')->put('products/pastel-primary.jpg', 'image');
    Storage::disk('public')->put('products/pastel-side.jpg', 'image');

    Product::factory()->create([
        'slug' => 'pastel-donut-box',
        'title' => 'Pastel Donut Box',
        'image_path' => 'products/pastel-primary.jpg',
        'product_images' => [
            'products/pastel-primary.jpg',
            'products/pastel-side.jpg',
        ],
        'image_url' => null,
    ]);

    $this->get(route('products.show'))
        ->assertSuccessful()
        ->assertSee('Pastel Donut Box')
        ->assertSee('products/pastel-primary.jpg', false)
        ->assertSee('products/pastel-side.jpg', false);
});
