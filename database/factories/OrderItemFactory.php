<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'product_slug' => fake()->slug(),
            'product_title' => fake()->words(3, true),
            'category' => fake()->randomElement(['Cakes', 'Coffees / Shakes', 'Cookies', 'Pastries']),
            'product_image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=900&q=80',
            'unit_price' => 120,
            'quantity' => 1,
            'line_total' => 120,
        ];
    }
}
