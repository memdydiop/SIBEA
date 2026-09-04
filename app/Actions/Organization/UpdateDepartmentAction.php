<?php

namespace App\Actions\Organization;

use App\Models\Department;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateDepartmentAction
{
    /**
     * Update an existing department.
     *
     * @param  array<string, mixed>  $input
     */
    public function __invoke(Department $department, array $input): Department
    {
        $validated = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('departments', 'code')->ignore($department->id)],
            'parent_id' => ['nullable', 'integer', 'different:id', 'exists:departments,id'],
            'description' => ['nullable', 'string'],
            'manager_id' => ['nullable', 'integer', 'exists:employees,id'],
            'is_active' => ['nullable', 'boolean'],
        ])->validate();

        $department->update([
            'name' => $validated['name'],
            'code' => strtoupper($validated['code']),
            'parent_id' => array_key_exists('parent_id', $validated) ? $validated['parent_id'] : $department->parent_id,
            'description' => array_key_exists('description', $validated) ? $validated['description'] : $department->description,
            'manager_id' => array_key_exists('manager_id', $validated) ? $validated['manager_id'] : $department->manager_id,
            'is_active' => array_key_exists('is_active', $validated) ? $validated['is_active'] : $department->is_active,
        ]);

        return $department;
    }
}
