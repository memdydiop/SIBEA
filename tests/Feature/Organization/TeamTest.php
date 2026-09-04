<?php

use App\Actions\Organization\AssignEmployeeToTeamAction;
use App\Actions\Organization\CreateTeamAction;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Team;
use App\Models\TeamMember;

test('can create team via action', function () {
    $department = Department::factory()->create();
    $leader = Employee::factory()->create();

    $action = app(CreateTeamAction::class);
    $team = $action([
        'department_id' => $department->id,
        'name' => 'Équipe Gros Œuvre',
        'code' => 'eq-go-1',
        'leader_id' => $leader->id,
        'description' => 'Équipe principale pour le gros œuvre',
    ]);

    expect($team)->toBeInstanceOf(Team::class)
        ->and($team->code)->toBe('EQ-GO-1')
        ->and($team->leader->id)->toBe($leader->id)
        ->and($team->department->id)->toBe($department->id);
});

test('can assign and manage team members', function () {
    $team = Team::factory()->create();
    $employee = Employee::factory()->create();

    $assignAction = app(AssignEmployeeToTeamAction::class);
    $membership = $assignAction([
        'team_id' => $team->id,
        'employee_id' => $employee->id,
        'role_in_team' => 'Ferrailleur',
        'joined_at' => now()->toDateString(),
    ]);

    expect($membership)->toBeInstanceOf(TeamMember::class)
        ->and($membership->role_in_team)->toBe('Ferrailleur')
        ->and($membership->is_active)->toBeTrue();

    expect($team->fresh()->members)->toHaveCount(1)
        ->and($team->fresh()->members->first()->id)->toBe($employee->id)
        ->and($employee->fresh()->teams->first()->id)->toBe($team->id);
});
