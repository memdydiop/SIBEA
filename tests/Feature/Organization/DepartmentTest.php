<?php

use App\Actions\Organization\CreateDepartmentAction;
use App\Actions\Organization\UpdateDepartmentAction;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Validation\ValidationException;

test('can create department via action', function () {
    $action = app(CreateDepartmentAction::class);

    $department = $action([
        'name' => 'Direction Technique',
        'code' => 'dtt',
        'description' => 'Direction technique et travaux',
    ]);

    expect($department)->toBeInstanceOf(Department::class)
        ->and($department->name)->toBe('Direction Technique')
        ->and($department->code)->toBe('DTT')
        ->and($department->is_active)->toBeTrue();

    $this->assertDatabaseHas('departments', [
        'id' => $department->id,
        'code' => 'DTT',
    ]);
});

test('department code must be unique', function () {
    Department::factory()->create(['code' => 'DG']);

    $action = app(CreateDepartmentAction::class);

    expect(fn () => $action([
        'name' => 'Autre Direction',
        'code' => 'DG',
    ]))->toThrow(ValidationException::class);
});

test('can create hierarchical departments and query roots and children', function () {
    $parent = Department::factory()->create(['name' => 'Direction Technique', 'parent_id' => null]);
    $child1 = Department::factory()->create(['name' => 'Bureau d\'Études', 'parent_id' => $parent->id]);
    $child2 = Department::factory()->create(['name' => 'Service Chantiers', 'parent_id' => $parent->id]);

    expect($parent->children)->toHaveCount(2)
        ->and($child1->parent->id)->toBe($parent->id);

    $roots = Department::roots()->get();
    expect($roots->pluck('id'))->toContain($parent->id)
        ->and($roots->pluck('id'))->not->toContain($child1->id);
});

test('can update department via action', function () {
    $department = Department::factory()->create([
        'name' => 'Old Name',
        'code' => 'OLD',
    ]);

    $manager = Employee::factory()->create();

    $action = app(UpdateDepartmentAction::class);
    $updated = $action($department, [
        'name' => 'New Name',
        'code' => 'NEW',
        'manager_id' => $manager->id,
    ]);

    expect($updated->name)->toBe('New Name')
        ->and($updated->code)->toBe('NEW')
        ->and($updated->manager_id)->toBe($manager->id);
});
