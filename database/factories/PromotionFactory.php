<?php

namespace Database\Factories;

use App\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Promotion>
 */
class PromotionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->bothify('PROMO##')),
            'kind' => Promotion::KIND_DISCOUNT,
            'discount_type' => fake()->randomElement([Promotion::DISCOUNT_PERCENTAGE, Promotion::DISCOUNT_FIXED]),
            'discount_value' => fake()->randomFloat(2, 5, 50),
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addMonth(),
            'is_active' => true,
            'announcement_title' => fake()->sentence(6),
            'announcement_body' => fake()->sentence(12),
            'announcement_cta' => 'Claim Offer',
        ];
    }

    public function fixed(float $amount): static
    {
        return $this->state(fn (array $attributes) => [
            'discount_type' => Promotion::DISCOUNT_FIXED,
            'discount_value' => $amount,
        ]);
    }

    public function percentage(float $percent): static
    {
        return $this->state(fn (array $attributes) => [
            'discount_type' => Promotion::DISCOUNT_PERCENTAGE,
            'discount_value' => $percent,
        ]);
    }

    public function ad(): static
    {
        return $this->state(fn (array $attributes) => [
            'code' => strtoupper(fake()->unique()->bothify('AD####')),
            'kind' => Promotion::KIND_AD,
            'discount_type' => Promotion::DISCOUNT_FIXED,
            'discount_value' => 0,
            'announcement_title' => null,
            'announcement_body' => null,
            'announcement_cta' => null,
            'image_path' => 'promotions/example-ad.jpg',
        ]);
    }
}
