<?php

namespace App\Policies;

use App\Models\MenuItem;
use App\Models\User;

class MenuItemPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('cms.manage');
    }

    public function view(User $user, MenuItem $menuItem): bool
    {
        return $user->can('cms.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('cms.manage');
    }

    public function update(User $user, MenuItem $menuItem): bool
    {
        return $user->can('cms.manage');
    }

    public function delete(User $user, MenuItem $menuItem): bool
    {
        return $user->can('cms.manage');
    }

    public function restore(User $user, MenuItem $menuItem): bool
    {
        return $user->can('cms.manage');
    }

    public function forceDelete(User $user, MenuItem $menuItem): bool
    {
        return $user->hasRole('super-admin');
    }
}
