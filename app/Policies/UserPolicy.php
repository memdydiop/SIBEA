<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('organization.view') || $user->hasRole('super-admin') || $user->hasRole('admin');
    }

    public function view(User $user, User $model): bool
    {
        return $user->can('organization.view') || $user->id === $model->id || $user->hasRole('super-admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('super-admin') || $user->hasRole('admin');
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasRole('super-admin') || $user->hasRole('admin') || $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasRole('super-admin') && $user->id !== $model->id;
    }

    public function restore(User $user, User $model): bool
    {
        return $user->hasRole('super-admin');
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasRole('super-admin');
    }
}
