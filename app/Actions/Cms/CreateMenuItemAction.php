<?php

namespace App\Actions\Cms;

use App\Models\MenuItem;
use Illuminate\Support\Facades\Validator;

class CreateMenuItemAction
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function __invoke(array $input): MenuItem
    {
        $validated = Validator::make($input, [
            'menu_id' => ['required', 'integer', 'exists:menus,id'],
            'parent_id' => ['nullable', 'integer', 'exists:menu_items,id'],
            'label' => ['required', 'string', 'max:150'],
            'url' => ['required', 'string', 'max:255'],
            'target' => ['nullable', 'string', 'in:_self,_blank'],
            'order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ])->validate();

        $validated['target'] = $validated['target'] ?? '_self';
        $validated['order'] = $validated['order'] ?? 0;
        $validated['is_active'] = $validated['is_active'] ?? true;

        return MenuItem::create($validated);
    }
}
