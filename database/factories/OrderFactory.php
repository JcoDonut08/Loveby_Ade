<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_number' => 'LBA-'.fake()->unique()->numberBetween(100000, 999999),
            'user_id' => User::factory(),
            'status' => Order::STATUS_PENDING,
            'full_name' => fake()->name(),
            'contact_number' => '+63-9'.fake()->numerify('#########'),
            'email_address' => fake()->safeEmail(),
            'complete_address' => fake()->address(),
            'delivery_notes' => fake()->optional()->sentence(),
            'payment_method' => 'Cash on Delivery',
            'subtotal' => 120,
            'delivery_fee' => 0,
            'discount' => 0,
            'total' => 120,
        ];
    }
}
