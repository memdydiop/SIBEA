<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Department>
 */
class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $words = fake()->unique()->words(2);
        $name = is_array($words) ? implode(' ', $words) : (string) $words;

        return [
            'parent_id' => null,
            'name' => ucfirst($name),
            'code' => strtoupper(fake()->unique()->lexify('DEP-???')),
            'description' => fake()->sentence(),
            'manager_id' => null,
            'is_active' => true,
        ];
    }
}
