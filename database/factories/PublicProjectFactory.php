<?php

namespace Database\Factories;

use App\Models\PublicProject;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PublicProject>
 */
class PublicProjectFactory extends Factory
{
    protected $model = PublicProject::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'slug' => Str::slug($title),
            'title' => $title,
            'category' => fake()->randomElement(['Bâtiment', 'Génie civil', 'VRD', 'Énergie']),
            'client_name' => fake()->company(),
            'location' => fake()->city(),
            'year' => fake()->numberBetween(2018, 2026),
            'description' => fake()->paragraphs(2, true),
            'key_figures' => null,
            'cover_image' => null,
            'gallery' => null,
            'is_featured' => false,
            'is_published' => true,
            'published_at' => now(),
        ];
    }
}
