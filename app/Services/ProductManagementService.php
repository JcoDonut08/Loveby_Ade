<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductManagementService
{
    /**
     * @param  array{title: string, description: string, category: string, price: numeric, stock: int|string}  $data
     */
    public function create(array $data, ?UploadedFile $image): Product
    {
        $imagePath = $image?->store('products', 'public');

        return Product::query()->create([
            'slug' => $this->uniqueSlug($data['title']),
            'title' => $data['title'],
            'description' => $data['description'],
            'category' => $data['category'],
            'price' => $data['price'],
            'stock' => (int) $data['stock'],
            'sold' => 0,
            'rating' => 0,
            'image_path' => $imagePath,
            'is_active' => true,
        ]);
    }

    /**
     * @param  array{title: string, description: string, category: string, price: numeric, stock: int|string}  $data
     */
    public function update(Product $product, array $data, ?UploadedFile $image): Product
    {
        $imagePath = $product->image_path;

        if ($image instanceof UploadedFile) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $imagePath = $image->store('products', 'public');
        }

        $product->update([
            'slug' => $this->uniqueSlug($data['title'], $product),
            'title' => $data['title'],
            'description' => $data['description'],
            'category' => $data['category'],
            'price' => $data['price'],
            'stock' => (int) $data['stock'],
            'image_path' => $imagePath,
        ]);

        return $product;
    }

    public function delete(Product $product): void
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();
    }

    private function uniqueSlug(string $title, ?Product $ignoreProduct = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 2;

        while (Product::query()
            ->where('slug', $slug)
            ->when($ignoreProduct instanceof Product, fn ($query) => $query->whereKeyNot($ignoreProduct->getKey()))
            ->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
