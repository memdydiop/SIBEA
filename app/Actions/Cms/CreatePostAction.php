<?php

namespace App\Actions\Cms;

use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CreatePostAction
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function __invoke(array $input): Post
    {
        $validated = Validator::make($input, [
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'slug' => ['nullable', 'string', 'max:150', 'regex:/^[a-z0-9-]+$/', 'unique:posts,slug'],
            'title' => ['required', 'string', 'max:200'],
            'category' => ['nullable', 'string', 'max:100'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'string', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'author_id' => ['nullable', 'integer', 'exists:users,id'],
        ])->validate();

        if (empty($validated['slug'])) {
            $validated['slug'] = $this->uniqueSlug($validated['title']);
        }

        $validated['author_id'] = $validated['author_id'] ?? auth()->id();
        $validated['is_published'] = $validated['is_published'] ?? false;
        $validated['published_at'] = $validated['published_at'] ?? ($validated['is_published'] ? now() : null);

        return Post::create($validated);
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (Post::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
