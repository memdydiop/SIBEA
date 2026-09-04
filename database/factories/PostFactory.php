<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'author_id' => User::factory(),
            'slug' => Str::slug($title),
            'title' => $title,
            'meta_title' => fake()->sentence(3),
            'meta_description' => fake()->paragraph(),
            'category' => fake()->randomElement(['Actualité', 'Chantier', 'Innovation', 'RSE']),
            'excerpt' => fake()->paragraph(),
            'content' => fake()->paragraphs(3, true),
            'cover_image' => null,
            'published_at' => now()->subDays(fake()->numberBetween(0, 30)),
            'is_published' => true,
        ];
    }

    /**
     * Indicate that the post is draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
