<?php

namespace App\Actions\Cms;

use App\Models\Expertise;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CreateExpertiseAction
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function __invoke(array $input): Expertise
    {
        $validated = Validator::make($input, [
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'slug' => ['nullable', 'string', 'max:150', 'regex:/^[a-z0-9-]+$/', 'unique:expertises,slug'],
            'title' => ['required', 'string', 'max:150'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'cover_image' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ])->validate();

        if (empty($validated['slug'])) {
            $validated['slug'] = $this->uniqueSlug($validated['title']);
        }

        $validated['order'] = $validated['order'] ?? 0;
        $validated['is_active'] = $validated['is_active'] ?? true;

        return Expertise::create($validated);
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (Expertise::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
