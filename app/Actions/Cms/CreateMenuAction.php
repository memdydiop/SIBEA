<?php

namespace App\Actions\Cms;

use App\Models\Menu;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CreateMenuAction
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function __invoke(array $input): Menu
    {
        $validated = Validator::make($input, [
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:150', 'regex:/^[a-z0-9-]+$/', 'unique:menus,slug'],
            'location' => ['nullable', 'string', 'max:50', 'in:header,footer,sidebar'],
        ])->validate();

        if (empty($validated['slug'])) {
            $validated['slug'] = $this->uniqueSlug($validated['name']);
        }

        $validated['location'] = $validated['location'] ?? 'header';

        return Menu::create($validated);
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (Menu::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
