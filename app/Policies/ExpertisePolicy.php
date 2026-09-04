<?php

namespace App\Policies;

use App\Models\Expertise;
use App\Models\User;

class ExpertisePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('cms.manage');
    }

    public function view(User $user, Expertise $expertise): bool
    {
        return $user->can('cms.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('cms.manage');
    }

    public function update(User $user, Expertise $expertise): bool
    {
        return $user->can('cms.manage');
    }

    public function delete(User $user, Expertise $expertise): bool
    {
        return $user->can('cms.manage');
    }

    public function restore(User $user, Expertise $expertise): bool
    {
        return $user->can('cms.manage');
    }

    public function forceDelete(User $user, Expertise $expertise): bool
    {
        return $user->hasRole('super-admin');
    }
}
