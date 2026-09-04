<?php

use App\Models\Department;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

test('super-admin can access departments page', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');

    $this->actingAs($user)
        ->get(route('admin.departments'))
        ->assertOk()
        ->assertSee('Départements');
});

test('user without permission cannot access departments', function () {
    $user = User::factory()->create();
    $user->assignRole('client');

    $this->actingAs($user)
        ->get(route('admin.departments'))
        ->assertForbidden();
});

test('can create department via livewire admin component', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    $this->actingAs($user);

    Livewire::test('pages::admin.departments')
        ->call('openCreate')
        ->set('name', 'Direction Test')
        ->set('code', 'DTST')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('departments', ['code' => 'DTST']);
    $this->assertDatabaseHas('audit_logs', ['action' => 'DEPARTMENT_CREATED']);
});

test('can create team via livewire', function () {
    $dept = Department::factory()->create();
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    $this->actingAs($user);

    Livewire::test('pages::admin.teams')
        ->call('openCreate')
        ->set('department_id', $dept->id)
        ->set('name', 'Equipe Test')
        ->set('code', 'EQ-TEST')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('teams', ['code' => 'EQ-TEST']);
});

test('can toggle user active via livewire users page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('super-admin');
    $target = User::factory()->create(['is_active' => true]);
    $this->actingAs($admin);

    Livewire::test('pages::admin.users')
        ->call('toggleActive', $target->id)
        ->assertHasNoErrors();

    expect($target->refresh()->is_active)->toBeFalse();
});

test('audit page is accessible to admin', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');

    $this->actingAs($user)
        ->get(route('admin.audit'))
        ->assertOk()
        ->assertSee('Journal d’audit');
});
