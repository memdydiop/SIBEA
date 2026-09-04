<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

test('roles and permissions are properly seeded', function () {
    expect(Role::where('name', 'super-admin')->exists())->toBeTrue()
        ->and(Role::where('name', 'conducteur-travaux')->exists())->toBeTrue()
        ->and(Role::where('name', 'chef-de-chantier')->exists())->toBeTrue()
        ->and(Permission::where('name', 'projects.view')->exists())->toBeTrue()
        ->and(Permission::where('name', 'reports.submit')->exists())->toBeTrue();
});

test('super admin has all permissions', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');

    expect($user->hasPermissionTo('projects.create'))->toBeTrue()
        ->and($user->hasPermissionTo('reports.validate'))->toBeTrue()
        ->and($user->hasPermissionTo('budgets.approve'))->toBeTrue()
        ->and($user->hasPermissionTo('lots.reserve'))->toBeTrue();
});

test('chef de chantier has site and daily report permissions but not budget approval', function () {
    $user = User::factory()->create();
    $user->assignRole('chef-de-chantier');

    expect($user->hasPermissionTo('reports.create'))->toBeTrue()
        ->and($user->hasPermissionTo('reports.submit'))->toBeTrue()
        ->and($user->hasPermissionTo('sites.view'))->toBeTrue()
        ->and($user->hasPermissionTo('budgets.approve'))->toBeFalse();
});
