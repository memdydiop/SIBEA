<?php

namespace App\Policies;

use App\Models\PublicProject;
use App\Models\User;

class PublicProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('cms.manage');
    }

    public function view(User $user, PublicProject $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('cms.manage');
    }

    public function update(User $user, PublicProject $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function delete(User $user, PublicProject $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function restore(User $user, PublicProject $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function forceDelete(User $user, PublicProject $modelInstance): bool
    {
        return $user->hasRole('super-admin');
    }
}
