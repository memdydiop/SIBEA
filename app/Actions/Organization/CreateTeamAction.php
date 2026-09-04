<?php

namespace App\Actions\Organization;

use App\Models\Team;
use Illuminate\Support\Facades\Validator;

class CreateTeamAction
{
    /**
     * Create a new team.
     *
     * @param  array<string, mixed>  $input
     */
    public function __invoke(array $input): Team
    {
        $validated = Validator::make($input, [
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:teams,code'],
            'leader_id' => ['nullable', 'integer', 'exists:employees,id'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ])->validate();

        return Team::create([
            'department_id' => $validated['department_id'],
            'name' => $validated['name'],
            'code' => strtoupper($validated['code']),
            'leader_id' => $validated['leader_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);
    }
}
