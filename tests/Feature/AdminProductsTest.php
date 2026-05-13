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
        ->assertSee('Upload image')
        ->assertSee('Product name')
        ->assertSee('Description')
        ->assertSee('Category')
        ->assertSee('Price')
        ->assertSee('Stock quantity')
        ->assertSee('Save product')
        ->assertDontSee('Rating')
        ->assertDontSee('data-admin-products', false)
        ->assertDontSee('data-product-grid', false)
        ->assertDontSee('data-product-search', false)
        ->assertDontSee('data-product-modal aria-hidden', false)
        ->assertSee('href="'.route('admin.products').'" aria-current="page"', false)
        ->assertDontSee('href="'.route('admin.dashboard').'" aria-current="page"', false)
        ->assertDontSee('href="'.route('admin.orders').'" aria-current="page"', false);
});

test('admin can add a product with a stored photo', function () {
    Storage::fake('public');

    $this->actingAs(adminUser())
        ->post(route('admin.products.store'), [
            'title' => 'Ube Cloud Cake',
            'description' => 'Soft ube cake with cream layers.',
            'category' => 'Cakes',
            'price' => 180,
            'stock' => 12,
            'image' => UploadedFile::fake()->create('ube-cake.jpg', 10, 'image/jpeg'),
        ])
        ->assertRedirect(route('admin.products'));

    $product = Product::query()->where('slug', 'ube-cloud-cake')->firstOrFail();

    expect($product->image_path)->not->toBeNull();
    expect((float) $product->rating)->toBe(0.0);
    Storage::disk('public')->assertExists($product->image_path);
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
    $product = Product::factory()->create([
        'image_path' => 'products/delete-me.jpg',
    ]);

    $this->actingAs(adminUser())
        ->delete(route('admin.products.destroy', $product))
        ->assertRedirect(route('admin.products'));

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);
    Storage::disk('public')->assertMissing('products/delete-me.jpg');
});
