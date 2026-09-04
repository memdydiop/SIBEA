<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

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
            'excerpt' => fake()->paragraph(),
            'content' => fake()->paragraphs(3, true),
            'cover_image' => null,
            'is_published' => true,
            'is_archived' => false,
            'published_at' => now()->subDays(fake()->numberBetween(0, 30)),
            'order' => fake()->numberBetween(0, 10),
        ];
    }
}
