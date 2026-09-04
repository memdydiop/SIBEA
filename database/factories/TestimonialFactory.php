<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimonial>
 */
class TestimonialFactory extends Factory
{
    protected $model = Testimonial::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_name' => fake()->name(),
            'company' => fake()->company(),
            'role' => fake()->jobTitle(),
            'content' => fake()->paragraphs(2, true),
            'rating' => fake()->numberBetween(4, 5),
            'avatar_url' => null,
            'order' => fake()->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}
