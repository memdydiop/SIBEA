<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('cms.manage');
    }

    public function view(User $user, Service $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('cms.manage');
    }

    public function update(User $user, Service $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function delete(User $user, Service $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function restore(User $user, Service $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function forceDelete(User $user, Service $modelInstance): bool
    {
        return $user->hasRole('super-admin');
    }
}
