<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->words(3, true);

        return [
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 999),
            'title' => Str::title($title),
            'category' => fake()->randomElement(['Cakes', 'Coffees / Shakes', 'Cookies', 'Pastries']),
            'description' => fake()->sentence(14),
            'price' => fake()->numberBetween(80, 150),
            'sold' => fake()->numberBetween(0, 240),
            'stock' => fake()->numberBetween(0, 30),
            'rating' => fake()->randomFloat(1, 4.2, 5.0),
            'image_url' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=900&q=80',
            'is_active' => true,
        ];
    }
}
