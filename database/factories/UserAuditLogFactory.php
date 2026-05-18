<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserAuditLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserAuditLog>
 */
class UserAuditLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'user_name' => fake()->name(),
            'user_email' => fake()->safeEmail(),
            'activity' => fake()->randomElement(['Login', 'Profile Updated', 'Order Placed']),
            'module' => fake()->randomElement(['Authentication', 'Account', 'Orders']),
            'description' => fake()->sentence(),
            'status' => 'success',
            'ip_address' => fake()->ipv4(),
            'user_agent' => 'Pest',
            'metadata' => null,
        ];
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'failed',
        ]);
    }
}
