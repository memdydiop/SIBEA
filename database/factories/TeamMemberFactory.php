<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeamMember>
 */
class TeamMemberFactory extends Factory
{
    protected $model = TeamMember::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'employee_id' => Employee::factory(),
            'role_in_team' => fake()->randomElement(['Chef d\'équipe', 'Ouvrier', 'Poseur', 'Manœuvre', 'Technicien']),
            'joined_at' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'left_at' => null,
            'is_active' => true,
        ];
    }
}
