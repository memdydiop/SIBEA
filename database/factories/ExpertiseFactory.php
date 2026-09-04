<?php

namespace Database\Factories;

use App\Models\Expertise;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Expertise>
 */
class ExpertiseFactory extends Factory
{
    protected $model = Expertise::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'slug' => Str::slug($title),
            'title' => $title,
            'excerpt' => fake()->paragraph(),
            'content' => fake()->paragraphs(3, true),
            'icon' => null,
            'cover_image' => null,
            'order' => fake()->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}
