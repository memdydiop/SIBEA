<?php

namespace App\Actions\Cms;

use App\Models\Service;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateServiceAction
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function __invoke(Service $service, array $input): Service
    {
        $validated = Validator::make($input, [
            'expertise_id' => ['nullable', 'integer', 'exists:expertises,id'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'slug' => ['required', 'string', 'max:150', 'regex:/^[a-z0-9-]+$/', Rule::unique('services', 'slug')->ignore($service->id)],
            'title' => ['required', 'string', 'max:150'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ])->validate();

        $service->update($validated);

        return $service;
    }
}
