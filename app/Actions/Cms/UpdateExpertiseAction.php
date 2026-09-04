<?php

namespace App\Actions\Cms;

use App\Models\Expertise;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateExpertiseAction
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function __invoke(Expertise $expertise, array $input): Expertise
    {
        $validated = Validator::make($input, [
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'slug' => ['required', 'string', 'max:150', 'regex:/^[a-z0-9-]+$/', Rule::unique('expertises', 'slug')->ignore($expertise->id)],
            'title' => ['required', 'string', 'max:150'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'cover_image' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ])->validate();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $expertise->update($validated);

        return $expertise;
    }
}
