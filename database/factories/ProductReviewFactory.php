<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductReview>
 */
class ProductReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'author_name' => fake()->name(),
            'rating' => fake()->numberBetween(4, 5),
            'body' => fake()->sentence(14),
            'media_paths' => [],
            'is_anonymous' => false,
        ];
    }

    public function anonymous(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_anonymous' => true,
        ]);
    }
}
