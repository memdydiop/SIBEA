<?php

namespace Database\Factories;

use App\Enums\ContractType;
use App\Enums\EmployeeStatus;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'department_id' => Department::factory(),
            'registration_number' => 'EMP-'.fake()->unique()->numerify('####').'-'.fake()->numerify('####'),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'job_title' => fake()->randomElement([
                'Conducteur de travaux',
                'Chef de chantier',
                'Ingénieur travaux',
                'Topographe',
                'Chef d\'équipe VRD',
                'Maçon qualifié',
                'Grutier',
                'Responsable QHSE',
            ]),
            'contract_type' => ContractType::Cdi,
            'status' => EmployeeStatus::Active,
            'hire_date' => fake()->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'end_date' => null,
            'hourly_cost_rate' => fake()->randomFloat(2, 15, 60),
            'daily_cost_rate' => fake()->randomFloat(2, 120, 480),
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_phone' => fake()->phoneNumber(),
            'notes' => fake()->optional()->sentence(),
            'is_public' => fake()->boolean(30),
        ];
    }
}
