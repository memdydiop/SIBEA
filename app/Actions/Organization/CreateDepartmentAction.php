<?php

namespace App\Actions\Organization;

use App\Models\Department;
use Illuminate\Support\Facades\Validator;

class CreateDepartmentAction
{
    /**
     * Create a new department or direction.
     *
     * @param  array<string, mixed>  $input
     */
    public function __invoke(array $input): Department
    {
        $validated = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:departments,code'],
            'parent_id' => ['nullable', 'integer', 'exists:departments,id'],
            'description' => ['nullable', 'string'],
            'manager_id' => ['nullable', 'integer', 'exists:employees,id'],
            'is_active' => ['nullable', 'boolean'],
        ])->validate();

        return Department::create([
            'name' => $validated['name'],
            'code' => strtoupper($validated['code']),
            'parent_id' => $validated['parent_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'manager_id' => $validated['manager_id'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);
    }
}
