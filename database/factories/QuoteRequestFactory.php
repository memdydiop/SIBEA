<?php

namespace Database\Factories;

use App\Enums\QuoteRequestStatus;
use App\Models\QuoteRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuoteRequest>
 */
class QuoteRequestFactory extends Factory
{
    protected $model = QuoteRequest::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reference' => 'QR-'.now()->year.'-'.fake()->unique()->numerify('####'),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'company' => fake()->company(),
            'role' => fake()->jobTitle(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'location' => fake()->city(),
            'service_type' => fake()->randomElement(['Gros œuvre', 'VRD', 'Énergie', 'Second œuvre']),
            'project_nature' => fake()->sentence(),
            'estimated_budget' => fake()->randomElement(['< 10M', '10-50M', '50-100M', '> 100M']),
            'desired_timeline' => fake()->randomElement(['Immédiat', '3 mois', '6 mois', '1 an']),
            'description' => fake()->paragraphs(2, true),
            'status' => QuoteRequestStatus::New,
            'consent' => true,
            'internal_notes' => null,
            'assigned_to' => null,
        ];
    }
}
