<?php

namespace App\Policies;

use App\Models\Program;
use App\Models\User;

class ProgramPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('cms.manage');
    }

    public function view(User $user, Program $program): bool
    {
        return $user->can('cms.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('cms.manage');
    }

    public function update(User $user, Program $program): bool
    {
        return $user->can('cms.manage');
    }

    public function delete(User $user, Program $program): bool
    {
        return $user->can('cms.manage');
    }

    public function restore(User $user, Program $program): bool
    {
        return $user->can('cms.manage');
    }

    public function forceDelete(User $user, Program $program): bool
    {
        return $user->hasRole('super-admin');
    }
}
