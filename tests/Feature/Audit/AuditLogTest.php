<?php

use App\Actions\Audit\LogAuditAction;
use App\Models\AuditLog;
use App\Models\Department;
use App\Models\User;

test('can create audit log via action', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $department = Department::factory()->create();

    $action = app(LogAuditAction::class);

    $log = $action('DEPARTMENT_CREATED', $department, null, ['name' => $department->name]);

    expect($log)->toBeInstanceOf(AuditLog::class)
        ->and($log->action)->toBe('DEPARTMENT_CREATED')
        ->and($log->auditable_type)->toBe($department->getMorphClass())
        ->and($log->auditable_id)->toBe($department->id)
        ->and($log->user_id)->toBe($user->id);

    $this->assertDatabaseHas('audit_logs', [
        'action' => 'DEPARTMENT_CREATED',
        'auditable_type' => $department->getMorphClass(),
        'auditable_id' => $department->id,
    ]);
});

test('audit log stores old and new values', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $action = app(LogAuditAction::class);

    $log = $action('USER_DEACTIVATED', $user, ['is_active' => true], ['is_active' => false]);

    expect($log->old_values)->toBe(['is_active' => true])
        ->and($log->new_values)->toBe(['is_active' => false]);
});

test('audit log morph relation works', function () {
    $department = Department::factory()->create();
    $log = AuditLog::factory()->create([
        'auditable_type' => $department->getMorphClass(),
        'auditable_id' => $department->id,
    ]);

    expect($log->auditable)->toBeInstanceOf(Department::class)
        ->and($log->auditable->id)->toBe($department->id);
});
