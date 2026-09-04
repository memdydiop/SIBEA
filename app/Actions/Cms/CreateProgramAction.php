<?php

namespace App\Actions\Cms;

use App\Models\Program;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CreateProgramAction
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function __invoke(array $input): Program
    {
        $validated = Validator::make($input, [
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'slug' => ['nullable', 'string', 'max:150', 'regex:/^[a-z0-9-]+$/', 'unique:programs,slug'],
            'title' => ['required', 'string', 'max:200'],
            'city' => ['nullable', 'string', 'max:100'],
            'municipality' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'total_area' => ['nullable', 'numeric', 'min:0', 'max:9999999'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'cover_path' => ['nullable', 'string', 'max:255'],
            'total_lots' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ])->validate();

        if (empty($validated['slug'])) {
            $validated['slug'] = $this->uniqueSlug($validated['title']);
        }

        $validated['total_lots'] = $validated['total_lots'] ?? 0;
        $validated['order'] = $validated['order'] ?? 0;
        $validated['is_published'] = $validated['is_published'] ?? true;
        $validated['published_at'] = $validated['published_at'] ?? ($validated['is_published'] ? now() : null);

        return Program::create($validated);
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (Program::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
