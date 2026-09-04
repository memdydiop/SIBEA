<?php

namespace App\Actions\Organization;

use App\Models\TeamMember;
use Illuminate\Support\Facades\Validator;

class AssignEmployeeToTeamAction
{
    /**
     * Assign or update an employee's membership in a team.
     *
     * @param  array<string, mixed>  $input
     */
    public function __invoke(array $input): TeamMember
    {
        $validated = Validator::make($input, [
            'team_id' => ['required', 'integer', 'exists:teams,id'],
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'role_in_team' => ['nullable', 'string', 'max:100'],
            'joined_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ])->validate();

        /** @var TeamMember $membership */
        $membership = TeamMember::updateOrCreate(
            [
                'team_id' => $validated['team_id'],
                'employee_id' => $validated['employee_id'],
            ],
            [
                'role_in_team' => $validated['role_in_team'] ?? null,
                'joined_at' => $validated['joined_at'] ?? now()->toDateString(),
                'is_active' => $validated['is_active'] ?? true,
            ]
        );

        return $membership;
    }
}
