<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Services\ProductCatalog;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (ProductCatalog::defaultProducts() as $product) {
            Product::query()->updateOrCreate(
                ['slug' => $product['slug']],
                [
                    'title' => $product['title'],
                    'category' => $product['category'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'sold' => $product['sold'],
                    'stock' => $product['stock'],
                    'rating' => $product['rating'],
                    'image_url' => $product['image'],
                    'is_active' => true,
                ],
            );
        }
    }
}
