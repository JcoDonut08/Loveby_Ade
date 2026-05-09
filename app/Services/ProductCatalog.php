<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProductCatalog
{
    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function all(): Collection
    {
        return collect($this->products())
            ->map(fn (array $product): array => $this->decorateProduct($product))
            ->values();
    }

    /**
     * @param  array{search?: string|null, category?: string|null, min_price?: int|string|null, max_price?: int|string|null}  $filters
     * @return Collection<int, array<string, mixed>>
     */
    public function filter(array $filters): Collection
    {
        return $this->all()
            ->when($filters['search'] ?? null, function (Collection $products, string $search): Collection {
                $needle = Str::lower($search);

                return $products->filter(function (array $product) use ($needle): bool {
                    $haystack = Str::lower(implode(' ', [
                        $product['title'],
                        $product['category'],
                        $product['description'],
                    ]));

                    return str_contains($haystack, $needle);
                });
            })
            ->when($filters['category'] ?? null, fn (Collection $products, string $category): Collection => $products
                ->where('category', $category))
            ->when($filters['min_price'] ?? null, fn (Collection $products, int|string $minimumPrice): Collection => $products
                ->filter(fn (array $product): bool => $product['price'] >= (int) $minimumPrice))
            ->when($filters['max_price'] ?? null, fn (Collection $products, int|string $maximumPrice): Collection => $products
                ->filter(fn (array $product): bool => $product['price'] <= (int) $maximumPrice))
            ->values();
    }

    /**
     * @return array<int, string>
     */
    public function categories(): array
    {
        return self::availableCategories();
    }

    /**
     * @return array<int, string>
     */
    public static function availableCategories(): array
    {
        return [
            'Brownies',
            'Cakes',
            'Cookies',
            'Donuts',
            'Pastries',
        ];
    }

    /**
     * @return array{min: int, max: int}
     */
    public function priceRange(): array
    {
        $prices = $this->all()->pluck('price');

        return [
            'min' => (int) $prices->min(),
            'max' => (int) $prices->max(),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(string $slug): ?array
    {
        return $this->all()
            ->first(fn (array $product): bool => $product['slug'] === $slug);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function recommendations(string $slug): Collection
    {
        return $this->all()
            ->reject(fn (array $product): bool => $product['slug'] === $slug)
            ->take(4)
            ->values();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function products(): array
    {
        return [
            [
                'slug' => 'pastel-donut-box',
                'title' => 'Pastel Donut Box',
                'category' => 'Donuts',
                'price' => 120,
                'sold' => 184,
                'stock' => 14,
                'rating' => 4.8,
                'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=900&q=80',
                'description' => 'A soft and colorful box of freshly baked donuts made for gifting, sharing, and sweet cravings. Each box is packed with pastel glaze, playful toppings, and a cozy bakery finish.',
                'gallery' => [
                    [
                        'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=1200&q=80',
                        'thumbnail' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=240&q=80',
                        'alt' => 'Pastel Donut Box product photo',
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=1200&q=80',
                        'thumbnail' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=240&q=80',
                        'alt' => 'Colorful donut product photo',
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1514517604298-cf80e0fb7f1e?auto=format&fit=crop&w=1200&q=80',
                        'thumbnail' => 'https://images.unsplash.com/photo-1514517604298-cf80e0fb7f1e?auto=format&fit=crop&w=240&q=80',
                        'alt' => 'Assorted glazed donuts product photo',
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1556913396-7a3c459ef68e?auto=format&fit=crop&w=1200&q=80',
                        'thumbnail' => 'https://images.unsplash.com/photo-1556913396-7a3c459ef68e?auto=format&fit=crop&w=240&q=80',
                        'alt' => 'Dessert box product photo',
                    ],
                ],
            ],
            [
                'slug' => 'chocolate-chip-cookies',
                'title' => 'Chocolate Chip Cookies',
                'category' => 'Cookies',
                'price' => 90,
                'sold' => 226,
                'stock' => 21,
                'rating' => 4.7,
                'image' => 'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=900&q=80',
                'description' => 'Golden cookies with soft centers, crisp edges, and generous chocolate chips baked for everyday snack boxes and after-school cravings.',
            ],
            [
                'slug' => 'mini-cake-cups',
                'title' => 'Mini Cake Cups',
                'category' => 'Cakes',
                'price' => 150,
                'sold' => 142,
                'stock' => 9,
                'rating' => 4.9,
                'image' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=900&q=80',
                'description' => 'Layered cake cups with fluffy sponge, creamy frosting, and colorful toppings sized for easy gifting or solo dessert moments.',
            ],
            [
                'slug' => 'berry-danish-set',
                'title' => 'Berry Danish Set',
                'category' => 'Pastries',
                'price' => 130,
                'sold' => 118,
                'stock' => 11,
                'rating' => 4.6,
                'image' => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=900&q=80',
                'description' => 'Buttery pastry layers filled with sweet berry notes and a light glaze for brunch tables, coffee breaks, and party trays.',
            ],
            [
                'slug' => 'strawberry-tartlets',
                'title' => 'Strawberry Tartlets',
                'category' => 'Pastries',
                'price' => 110,
                'sold' => 154,
                'stock' => 13,
                'rating' => 4.8,
                'image' => 'https://images.unsplash.com/photo-1464305795204-6f5bbfc7fb81?auto=format&fit=crop&w=900&q=80',
                'description' => 'Mini tart shells layered with smooth cream and bright strawberry toppings for a fresh, gift-ready dessert bite.',
            ],
            [
                'slug' => 'vanilla-cream-puffs',
                'title' => 'Vanilla Cream Puffs',
                'category' => 'Pastries',
                'price' => 100,
                'sold' => 167,
                'stock' => 18,
                'rating' => 4.7,
                'image' => 'https://images.unsplash.com/photo-1571115764595-644a1f56a55c?auto=format&fit=crop&w=900&q=80',
                'description' => 'Airy choux pastry filled with vanilla cream and a delicate powdered finish for a light dessert box favorite.',
            ],
            [
                'slug' => 'caramel-brownie-bites',
                'title' => 'Caramel Brownie Bites',
                'category' => 'Brownies',
                'price' => 140,
                'sold' => 131,
                'stock' => 8,
                'rating' => 4.9,
                'image' => 'https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?auto=format&fit=crop&w=900&q=80',
                'description' => 'Rich brownie squares with a caramel finish, made bite-sized for sharing and easy checkout add-ons.',
            ],
            [
                'slug' => 'milk-tea-cookie-tin',
                'title' => 'Milk Tea Cookie Tin',
                'category' => 'Cookies',
                'price' => 80,
                'sold' => 205,
                'stock' => 16,
                'rating' => 4.5,
                'image' => 'https://images.unsplash.com/photo-1515037893149-de7f840978e2?auto=format&fit=crop&w=900&q=80',
                'description' => 'A budget-friendly cookie tin with cozy milk tea notes and crisp, snackable pieces for quick sweet breaks.',
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $product
     * @return array<string, mixed>
     */
    private function decorateProduct(array $product): array
    {
        $gallery = $product['gallery'] ?? [
            [
                'image' => Str::replace('w=900', 'w=1200', $product['image']),
                'thumbnail' => Str::replace('w=900', 'w=240', $product['image']),
                'alt' => $product['title'].' product photo',
            ],
        ];

        return [
            ...$product,
            'gallery' => $gallery,
            'sold_label' => $product['sold'].' sold',
            'stock_label' => $product['stock'].' left',
            'stock_detail_label' => $product['stock'].' stocks left',
            'show_url' => $product['slug'] === 'pastel-donut-box'
                ? route('products.show')
                : route('products.show-by-slug', $product['slug']),
        ];
    }
}
