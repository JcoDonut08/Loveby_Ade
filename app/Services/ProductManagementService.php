<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductManagementService
{
    /**
     * @param  array{title: string, description: string, category: string, price: numeric, stock: int|string}  $data
     * @param  array<int, UploadedFile>  $images
     */
    public function create(array $data, array $images): Product
    {
        $imagePaths = $this->storeImages($images);
        $primaryImagePath = $imagePaths[0] ?? null;

        return Product::query()->create([
            'slug' => $this->uniqueSlug($data['title']),
            'title' => $data['title'],
            'description' => $data['description'],
            'category' => $data['category'],
            'price' => $data['price'],
            'stock' => (int) $data['stock'],
            'sold' => 0,
            'rating' => 0,
            'image_path' => $primaryImagePath,
            'product_images' => $imagePaths,
            'image_url' => $this->publicImageUrl($primaryImagePath),
            'is_active' => true,
        ]);
    }

    /**
     * @param  array{title: string, description: string, category: string, price: numeric, stock: int|string}  $data
     * @param  array<int, UploadedFile>  $images
     * @param  array<int, string>|null  $existingImagePaths
     */
    public function update(Product $product, array $data, array $images, ?array $existingImagePaths = null): Product
    {
        $currentImagePaths = $this->currentImagePaths($product);
        $imagesWereSubmitted = $images !== [] || $existingImagePaths !== null;

        if ($existingImagePaths === null && $images !== []) {
            $this->deleteStoredImages($product);

            $imagePaths = $this->storeImages($images);
        } elseif ($existingImagePaths !== null) {
            $imagePaths = collect($existingImagePaths)
                ->filter(fn (string $path): bool => in_array($path, $currentImagePaths, true))
                ->unique()
                ->take(4)
                ->values()
                ->all();

            $imagePaths = [
                ...$imagePaths,
                ...$this->storeImages($images, 4 - count($imagePaths)),
            ];

            $this->deleteImagePaths(
                collect($currentImagePaths)
                    ->diff($imagePaths)
                    ->values()
                    ->all()
            );
        } else {
            $imagePaths = $currentImagePaths;
        }

        $primaryImagePath = $imagePaths[0] ?? null;

        $product->update([
            'slug' => $this->uniqueSlug($data['title'], $product),
            'title' => $data['title'],
            'description' => $data['description'],
            'category' => $data['category'],
            'price' => $data['price'],
            'stock' => (int) $data['stock'],
            'image_path' => $primaryImagePath,
            'product_images' => $imagePaths,
            'image_url' => $imagesWereSubmitted ? $this->publicImageUrl($primaryImagePath) : $product->image_url,
        ]);

        return $product;
    }

    public function delete(Product $product): void
    {
        $this->deleteStoredImages($product);

        $product->delete();
    }

    /**
     * @param  iterable<Product>  $products
     */
    public function deleteMany(iterable $products): int
    {
        $deletedCount = 0;

        foreach ($products as $product) {
            $this->delete($product);
            $deletedCount++;
        }

        return $deletedCount;
    }

    /**
     * @param  array<int, UploadedFile>  $images
     * @return array<int, string>
     */
    private function storeImages(array $images, int $limit = 4): array
    {
        return collect($images)
            ->filter(fn (mixed $image): bool => $image instanceof UploadedFile)
            ->take(max(0, $limit))
            ->map(fn (UploadedFile $image): string => $image->store('products', 'public'))
            ->values()
            ->all();
    }

    private function deleteStoredImages(Product $product): void
    {
        $this->deleteImagePaths($this->currentImagePaths($product));
    }

    /**
     * @param  array<int, string>  $paths
     */
    private function deleteImagePaths(array $paths): void
    {
        foreach ($paths as $path) {
            $disk = Storage::disk('public');

            if ($disk->delete($path)) {
                continue;
            }

            $absolutePath = $disk->path($path);

            if (is_file($absolutePath)) {
                File::delete($absolutePath);
            }
        }
    }

    /**
     * @return array<int, string>
     */
    private function currentImagePaths(Product $product): array
    {
        return collect($product->product_images ?: [])
            ->when($product->image_path, fn ($paths) => $paths->prepend($product->image_path))
            ->filter()
            ->unique()
            ->take(4)
            ->values()
            ->all();
    }

    private function publicImageUrl(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        return Storage::disk('public')->url($path);
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
