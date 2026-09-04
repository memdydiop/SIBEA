<?php

use App\Actions\Organization\CreateEmployeeAction;
use App\Actions\Organization\UpdateEmployeeAction;
use App\Enums\ContractType;
use App\Enums\EmployeeStatus;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Validation\ValidationException;

test('can create employee with auto generated matricule', function () {
    $department = Department::factory()->create();
    $action = app(CreateEmployeeAction::class);

    $employee = $action([
        'department_id' => $department->id,
        'first_name' => 'Kouamé',
        'last_name' => 'Konan',
        'email' => 'kouame.konan@example.com',
        'job_title' => 'Chef de chantier',
        'contract_type' => ContractType::Cdi->value,
        'status' => EmployeeStatus::Active->value,
    ]);

    expect($employee)->toBeInstanceOf(Employee::class)
        ->and($employee->registration_number)->toMatch('/^EMP-\d{4}-\d{4}$/')
        ->and($employee->full_name)->toBe('Kouamé Konan')
        ->and($employee->department->id)->toBe($department->id);
});

test('can create employee associated with a user account', function () {
    $user = User::factory()->create();
    $action = app(CreateEmployeeAction::class);

    $employee = $action([
        'user_id' => $user->id,
        'registration_number' => 'EMP-9999-9999',
        'first_name' => 'Jean',
        'last_name' => 'Dupont',
        'job_title' => 'Conducteur de travaux',
        'contract_type' => ContractType::Cdi->value,
        'status' => EmployeeStatus::Active->value,
    ]);

    expect($employee->user_id)->toBe($user->id)
        ->and($user->fresh()->employee->id)->toBe($employee->id);
});

test('employee registration number must be unique', function () {
    Employee::factory()->create(['registration_number' => 'EMP-0042-0042']);

    $action = app(CreateEmployeeAction::class);

    expect(fn () => $action([
        'registration_number' => 'EMP-0042-0042',
        'first_name' => 'Autre',
        'last_name' => 'Employé',
        'job_title' => 'Maçon',
        'contract_type' => ContractType::Cdi->value,
        'status' => EmployeeStatus::Active->value,
    ]))->toThrow(ValidationException::class);
});

test('can update employee details', function () {
    $employee = Employee::factory()->create([
        'first_name' => 'Paul',
        'last_name' => 'Martin',
    ]);

    $action = app(UpdateEmployeeAction::class);
    $updated = $action($employee, [
        'registration_number' => $employee->registration_number,
        'first_name' => 'Paul-Henri',
        'last_name' => 'Martin',
        'job_title' => 'Directeur Technique',
        'contract_type' => ContractType::Cdi->value,
        'status' => EmployeeStatus::Active->value,
        'hourly_cost_rate' => 45.50,
    ]);

    expect($updated->first_name)->toBe('Paul-Henri')
        ->and((float) $updated->hourly_cost_rate)->toBe(45.50);
});

test('active scope returns only active employees', function () {
    $active = Employee::factory()->create(['status' => EmployeeStatus::Active]);
    $onLeave = Employee::factory()->create(['status' => EmployeeStatus::OnLeave]);
    $terminated = Employee::factory()->create(['status' => EmployeeStatus::Terminated]);

    $activeEmployees = Employee::active()->get();

    expect($activeEmployees->pluck('id'))->toContain($active->id)
        ->and($activeEmployees->pluck('id'))->not->toContain($onLeave->id)
        ->and($activeEmployees->pluck('id'))->not->toContain($terminated->id);
});
