<?php

namespace App\Actions\Cms;

use App\Models\Menu;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateMenuAction
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function __invoke(Menu $menu, array $input): Menu
    {
        $validated = Validator::make($input, [
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:150', 'regex:/^[a-z0-9-]+$/', Rule::unique('menus', 'slug')->ignore($menu->id)],
            'location' => ['nullable', 'string', 'max:50', 'in:header,footer,sidebar'],
        ])->validate();

        $menu->update($validated);

        return $menu;
    }
}
