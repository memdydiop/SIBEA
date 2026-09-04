<?php

use App\Models\AuditLog;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::livewire('departments', 'pages::admin.departments')
        ->can('viewAny', Department::class)
        ->name('departments');

    Route::livewire('teams', 'pages::admin.teams')
        ->can('viewAny', Team::class)
        ->name('teams');

    Route::livewire('employees', 'pages::admin.employees')
        ->can('viewAny', Employee::class)
        ->name('employees');

    Route::livewire('users', 'pages::admin.users')
        ->can('viewAny', User::class)
        ->name('users');

    Route::livewire('audit', 'pages::admin.audit')
        ->can('viewAny', AuditLog::class)
        ->name('audit');
});
