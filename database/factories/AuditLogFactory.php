<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AuditLog>
 */
class AuditLogFactory extends Factory
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
            'action' => fake()->randomElement(['USER_CREATED', 'PROJECT_CREATED', 'DEPARTMENT_CREATED', 'ROLE_ASSIGNED']),
            'auditable_type' => User::class,
            'auditable_id' => 1,
            'old_values' => null,
            'new_values' => ['name' => fake()->name()],
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }
}
