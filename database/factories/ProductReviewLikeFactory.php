<?php

namespace Database\Factories;

use App\Models\ProductReview;
use App\Models\ProductReviewLike;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductReviewLike>
 */
class ProductReviewLikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_review_id' => ProductReview::factory(),
            'user_id' => User::factory(),
        ];
    }
}
