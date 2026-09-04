<?php

namespace App\Policies;

use App\Models\Partner;
use App\Models\User;

class PartnerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('cms.manage');
    }

    public function view(User $user, Partner $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('cms.manage');
    }

    public function update(User $user, Partner $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function delete(User $user, Partner $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function restore(User $user, Partner $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function forceDelete(User $user, Partner $modelInstance): bool
    {
        return $user->hasRole('super-admin');
    }
}
