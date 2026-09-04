<?php

namespace App\Actions\Cms;

use App\Models\Page;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdatePageAction
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function __invoke(Page $page, array $input): Page
    {
        $validated = Validator::make($input, [
            'slug' => ['required', 'string', 'max:150', 'regex:/^[a-z0-9-]+$/', Rule::unique('pages', 'slug')->ignore($page->id)],
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
            $validated['slug'] = Str::slug($validated['title']);
        }

        if (! empty($validated['is_archived'])) {
            $validated['is_published'] = false;
        }

        $page->update($validated);

        return $page;
    }
}
