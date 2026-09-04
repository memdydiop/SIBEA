<?php

use App\Actions\User\ActivateUserAction;
use App\Actions\User\DeactivateUserAction;
use App\Models\User;

test('can deactivate user via action and creates audit', function () {
    $user = User::factory()->create(['is_active' => true]);

    $action = app(DeactivateUserAction::class);
    $updated = $action($user);

    expect($updated->is_active)->toBeFalse();
    $this->assertDatabaseHas('users', ['id' => $user->id, 'is_active' => 0]);
    $this->assertDatabaseHas('audit_logs', ['action' => 'USER_DEACTIVATED', 'auditable_id' => $user->id]);
});

test('can activate user via action', function () {
    $user = User::factory()->inactive()->create();

    $action = app(ActivateUserAction::class);
    $updated = $action($user);

    expect($updated->is_active)->toBeTrue();
    $this->assertDatabaseHas('audit_logs', ['action' => 'USER_ACTIVATED', 'auditable_id' => $user->id]);
});

test('inactive user cannot authenticate', function () {
    $user = User::factory()->inactive()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertSessionHasErrorsIn('email');
    $this->assertGuest();
});

test('active user can authenticate', function () {
    $user = User::factory()->create(['is_active' => true]);

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticatedAs($user);
});

test('user is active by default', function () {
    $user = User::factory()->create();

    expect($user->is_active)->toBeTrue()
        ->and($user->isActive())->toBeTrue();
});
