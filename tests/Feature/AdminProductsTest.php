<?php

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('admin products page renders the catalog management workspace', function () {
    Product::factory()->create([
        'title' => 'Ube Cloud Cake',
        'category' => 'Cakes',
        'stock' => 12,
    ]);

    $this->actingAs(adminUser())
        ->get(route('admin.products'))
        ->assertSuccessful()
        ->assertSee('Products')
        ->assertSee('Manage your dessert catalog and inventory.')
        ->assertSee('Search desserts...')
        ->assertSee('Pastries')
        ->assertSee('Cakes')
        ->assertSee('Cookies')
        ->assertSee('Ube Cloud Cake')
        ->assertSee('Products per page')
        ->assertSee('Previous')
        ->assertSee('Next')
        ->assertSee('Add product')
        ->assertSee('Edit Product')
        ->assertSee('Delete Product')
        ->assertSee('Upload images')
        ->assertSee('Product name')
        ->assertSee('Description')
        ->assertSee('Category')
        ->assertSee('Price')
        ->assertSee('Stock quantity')
        ->assertSee('Save product')
        ->assertDontSee('Rating')
        ->assertSee('data-admin-products', false)
        ->assertSee('data-backend-products="true"', false)
        ->assertSee('data-product-search-form', false)
        ->assertSee('data-product-results-grid', false)
        ->assertSee('name="images[]"', false)
        ->assertSee('multiple', false)
        ->assertSee('data-product-existing-images', false)
        ->assertSee('Maximum of 4 images, 5 MB each.')
        ->assertDontSee('data-product-grid', false)
        ->assertDontSee('data-product-modal aria-hidden', false)
        ->assertSee('href="'.route('admin.products').'" aria-current="page"', false)
        ->assertDontSee('href="'.route('admin.dashboard').'" aria-current="page"', false)
        ->assertDontSee('href="'.route('admin.orders').'" aria-current="page"', false);
});

test('admin can add a product with stored gallery images', function () {
    Storage::fake('public');

    $this->actingAs(adminUser())
        ->post(route('admin.products.store'), [
            'title' => 'Ube Cloud Cake',
            'description' => 'Soft ube cake with cream layers.',
            'category' => 'Cakes',
            'price' => 180,
            'stock' => 12,
            'images' => [
                UploadedFile::fake()->create('ube-cake-primary.jpg', 10, 'image/jpeg'),
                UploadedFile::fake()->create('ube-cake-side.jpg', 10, 'image/jpeg'),
                UploadedFile::fake()->create('ube-cake-box.jpg', 10, 'image/jpeg'),
            ],
        ])
        ->assertRedirect(route('admin.products'));

    $product = Product::query()->where('slug', 'ube-cloud-cake')->firstOrFail();

    expect($product->image_path)->not->toBeNull();
    expect($product->product_images)->toHaveCount(3)
        ->and($product->image_path)->toBe($product->product_images[0]);
    expect((float) $product->rating)->toBe(0.0);
    Storage::disk('public')->assertExists($product->image_path);
    Storage::disk('public')->assertExists($product->product_images[1]);
});

test('admin product uploads are limited to four images', function () {
    $this->actingAs(adminUser())
        ->post(route('admin.products.store'), [
            'title' => 'Ube Cloud Cake',
            'description' => 'Soft ube cake with cream layers.',
            'category' => 'Cakes',
            'price' => 180,
            'stock' => 12,
            'images' => [
                UploadedFile::fake()->create('one.jpg', 10, 'image/jpeg'),
                UploadedFile::fake()->create('two.jpg', 10, 'image/jpeg'),
                UploadedFile::fake()->create('three.jpg', 10, 'image/jpeg'),
                UploadedFile::fake()->create('four.jpg', 10, 'image/jpeg'),
                UploadedFile::fake()->create('five.jpg', 10, 'image/jpeg'),
            ],
        ])
        ->assertSessionHasErrors('images');
});

test('admin product images can be up to five megabytes each', function () {
    Storage::fake('public');

    $this->actingAs(adminUser())
        ->post(route('admin.products.store'), [
            'title' => 'Ube Cloud Cake',
            'description' => 'Soft ube cake with cream layers.',
            'category' => 'Cakes',
            'price' => 180,
            'stock' => 12,
            'images' => [
                UploadedFile::fake()->create('five-megabytes.jpg', 5000, 'image/jpeg'),
            ],
        ])
        ->assertRedirect(route('admin.products'))
        ->assertSessionHasNoErrors();

    $product = Product::query()->where('slug', 'ube-cloud-cake')->firstOrFail();

    expect($product->image_path)->not->toBeNull();
    Storage::disk('public')->assertExists($product->image_path);
});

test('admin product images cannot be greater than five megabytes each', function () {
    $this->actingAs(adminUser())
        ->post(route('admin.products.store'), [
            'title' => 'Ube Cloud Cake',
            'description' => 'Soft ube cake with cream layers.',
            'category' => 'Cakes',
            'price' => 180,
            'stock' => 12,
            'images' => [
                UploadedFile::fake()->create('too-large.jpg', 5001, 'image/jpeg'),
            ],
        ])
        ->assertSessionHasErrors('images.0');
});

test('admin product search can refresh the product section without a full page load', function () {
    Product::factory()->create([
        'title' => 'Ube Cloud Cake',
        'category' => 'Cakes',
    ]);
    Product::factory()->create([
        'title' => 'Chocolate Chip Cookies',
        'category' => 'Cookies',
    ]);

    $this->actingAs(adminUser())
        ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
        ->getJson(route('admin.products', ['search' => 'Ube']))
        ->assertSuccessful()
        ->assertJson(fn ($json) => $json
            ->whereType('html', 'string')
            ->etc()
        )
        ->assertSee('Ube Cloud Cake')
        ->assertDontSee('Chocolate Chip Cookies');
});

test('admin can replace a product gallery', function () {
    Storage::fake('public');

    Storage::disk('public')->put('products/old.jpg', 'image');
    $product = Product::factory()->create([
        'title' => 'Old Cake',
        'slug' => 'old-cake',
        'image_path' => 'products/old.jpg',
        'product_images' => ['products/old.jpg'],
    ]);

    $this->actingAs(adminUser())
        ->patch(route('admin.products.update', $product), [
            'title' => 'Updated Cake',
            'description' => 'Freshly updated cake description.',
            'category' => 'Cakes',
            'price' => 220,
            'stock' => 5,
            'images' => [
                UploadedFile::fake()->create('new-primary.jpg', 10, 'image/jpeg'),
                UploadedFile::fake()->create('new-side.jpg', 10, 'image/jpeg'),
            ],
        ])
        ->assertRedirect(route('admin.products'));

    $product->refresh();

    expect($product->product_images)->toHaveCount(2)
        ->and($product->image_path)->toBe($product->product_images[0]);
    Storage::disk('public')->assertMissing('products/old.jpg');
    Storage::disk('public')->assertExists($product->product_images[0]);
    Storage::disk('public')->assertExists($product->product_images[1]);
});

test('admin can append gallery images while editing a product', function () {
    Storage::fake('public');

    Storage::disk('public')->put('products/current-primary.jpg', 'image');
    $product = Product::factory()->create([
        'title' => 'Current Cake',
        'slug' => 'current-cake',
        'image_path' => 'products/current-primary.jpg',
        'product_images' => ['products/current-primary.jpg'],
    ]);

    $this->actingAs(adminUser())
        ->patch(route('admin.products.update', $product), [
            'title' => 'Current Cake',
            'description' => 'Freshly edited cake description.',
            'category' => 'Cakes',
            'price' => 220,
            'stock' => 5,
            'existing_images' => ['products/current-primary.jpg'],
            'images' => [
                UploadedFile::fake()->create('new-side.jpg', 10, 'image/jpeg'),
                UploadedFile::fake()->create('new-box.jpg', 10, 'image/jpeg'),
            ],
        ])
        ->assertRedirect(route('admin.products'));

    $product->refresh();

    expect($product->product_images)->toHaveCount(3)
        ->and($product->product_images[0])->toBe('products/current-primary.jpg')
        ->and($product->image_path)->toBe('products/current-primary.jpg');

    Storage::disk('public')->assertExists('products/current-primary.jpg');
    Storage::disk('public')->assertExists($product->product_images[1]);
    Storage::disk('public')->assertExists($product->product_images[2]);
});

test('admin can update a product', function () {
    $product = Product::factory()->create([
        'title' => 'Old Cake',
        'slug' => 'old-cake',
        'category' => 'Cakes',
        'rating' => 0,
    ]);

    $this->actingAs(adminUser())
        ->patch(route('admin.products.update', $product), [
            'title' => 'New Cake',
            'description' => 'Freshly updated cake description.',
            'category' => 'Pastries',
            'price' => 220,
            'stock' => 5,
        ])
        ->assertRedirect(route('admin.products'));

    expect($product->refresh())
        ->title->toBe('New Cake')
        ->slug->toBe('new-cake')
        ->category->toBe('Pastries')
        ->stock->toBe(5)
        ->and((float) $product->rating)->toBe(0.0);
});

test('admin can delete a product', function () {
    Storage::fake('public');

    Storage::disk('public')->put('products/delete-me.jpg', 'image');
    Storage::disk('public')->put('products/delete-me-side.jpg', 'image');
    $product = Product::factory()->create([
        'image_path' => 'products/delete-me.jpg',
        'product_images' => [
            'products/delete-me.jpg',
            'products/delete-me-side.jpg',
        ],
    ]);

    $this->actingAs(adminUser())
        ->delete(route('admin.products.destroy', $product))
        ->assertRedirect(route('admin.products'));

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);
    Storage::disk('public')->assertMissing('products/delete-me.jpg');
    Storage::disk('public')->assertMissing('products/delete-me-side.jpg');
});
