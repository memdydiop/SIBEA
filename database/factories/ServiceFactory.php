<?php

namespace Database\Factories;

use App\Models\Expertise;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'expertise_id' => Expertise::factory(),
            'slug' => Str::slug($title),
            'title' => $title,
            'meta_title' => fake()->sentence(3),
            'meta_description' => fake()->paragraph(),
            'excerpt' => fake()->paragraph(),
            'content' => fake()->paragraphs(2, true),
            'icon' => null,
            'order' => fake()->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}
