<?php

namespace App\Actions\Cms;

use App\Models\Page;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CreatePageAction
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function __invoke(array $input): Page
    {
        $validated = Validator::make($input, [
            'slug' => ['nullable', 'string', 'max:150', 'regex:/^[a-z0-9-]+$/', 'unique:pages,slug'],
            'title' => ['required', 'string', 'max:200'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'string', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
            'is_archived' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ])->validate();

        if (empty($validated['slug'])) {
            $validated['slug'] = $this->uniqueSlug($validated['title']);
        }

        $validated['order'] = $validated['order'] ?? 0;
        $validated['is_published'] = $validated['is_published'] ?? false;
        $validated['is_archived'] = $validated['is_archived'] ?? false;
        if ($validated['is_archived']) {
            $validated['is_published'] = false;
        }
        $validated['published_at'] = $validated['published_at'] ?? ($validated['is_published'] ? now() : null);

        return Page::create($validated);
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (Page::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
