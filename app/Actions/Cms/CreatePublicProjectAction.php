<?php

namespace App\Actions\Cms;

use App\Models\PublicProject;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CreatePublicProjectAction
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function __invoke(array $input): PublicProject
    {
        $validated = Validator::make($input, [
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'slug' => ['nullable', 'string', 'max:150', 'regex:/^[a-z0-9-]+$/', 'unique:public_projects,slug'],
            'title' => ['required', 'string', 'max:200'],
            'category' => ['required', 'string', 'max:100'],
            'client_name' => ['nullable', 'string', 'max:150'],
            'location' => ['nullable', 'string', 'max:150'],
            'year' => ['nullable', 'integer', 'min:2000', 'max:2030'],
            'description' => ['nullable', 'string'],
            'key_figures' => ['nullable', 'array'],
            'cover_image' => ['nullable', 'string', 'max:255'],
            'gallery' => ['nullable', 'array'],
            'is_featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ])->validate();

        if (empty($validated['slug'])) {
            $validated['slug'] = $this->uniqueSlug($validated['title']);
        }

        $validated['is_featured'] = $validated['is_featured'] ?? false;
        $validated['is_published'] = $validated['is_published'] ?? true;
        $validated['published_at'] = $validated['published_at'] ?? ($validated['is_published'] ? now() : null);

        return PublicProject::create($validated);
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (PublicProject::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
