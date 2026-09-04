<?php

namespace App\Actions\Cms;

use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdatePostAction
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function __invoke(Post $post, array $input): Post
    {
        $validated = Validator::make($input, [
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'slug' => ['required', 'string', 'max:150', 'regex:/^[a-z0-9-]+$/', Rule::unique('posts', 'slug')->ignore($post->id)],
            'title' => ['required', 'string', 'max:200'],
            'category' => ['nullable', 'string', 'max:100'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'string', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ])->validate();

        $post->update($validated);

        return $post;
    }
}
