<?php

namespace App\Actions\Cms;

use App\Models\Program;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateProgramAction
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function __invoke(Program $program, array $input): Program
    {
        $validated = Validator::make($input, [
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'slug' => ['nullable', 'string', 'max:150', 'regex:/^[a-z0-9-]+$/', Rule::unique('programs', 'slug')->ignore($program->id)],
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
            $validated['slug'] = Str::slug($validated['title']);
        }

        $program->update($validated);

        return $program;
    }
}
