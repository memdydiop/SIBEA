<?php

namespace Database\Factories;

use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Program>
 */
class ProgramFactory extends Factory
{
    protected $model = Program::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'slug' => Str::slug($title),
            'title' => $title,
            'meta_title' => fake()->sentence(3),
            'meta_description' => fake()->paragraph(),
            'city' => fake()->city(),
            'municipality' => fake()->city(),
            'district' => fake()->streetName(),
            'total_area' => fake()->randomFloat(2, 1000, 50000),
            'excerpt' => fake()->paragraph(),
            'description' => fake()->paragraphs(3, true),
            'cover_path' => null,
            'total_lots' => fake()->numberBetween(5, 50),
            'is_published' => true,
            'published_at' => now(),
            'order' => fake()->numberBetween(0, 10),
        ];
    }

    /**
     * Indicate that the program is draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
