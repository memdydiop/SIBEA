<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Team>
 */
class TeamFactory extends Factory
{
    protected $model = Team::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $words = fake()->words(2);
        $name = is_array($words) ? implode(' ', $words) : (string) $words;

        return [
            'department_id' => Department::factory(),
            'name' => 'Équipe '.$name,
            'code' => strtoupper(fake()->unique()->lexify('EQ-???')),
            'leader_id' => null,
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
