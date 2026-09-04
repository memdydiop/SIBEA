<?php

namespace App\Actions\Cms;

use App\Models\PublicProject;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdatePublicProjectAction
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function __invoke(PublicProject $project, array $input): PublicProject
    {
        $validated = Validator::make($input, [
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'slug' => ['required', 'string', 'max:150', 'regex:/^[a-z0-9-]+$/', Rule::unique('public_projects', 'slug')->ignore($project->id)],
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

        $project->update($validated);

        return $project;
    }
}
