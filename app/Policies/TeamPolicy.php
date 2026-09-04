<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;

class TeamPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('organization.view') || $user->can('teams.manage');
    }

    public function view(User $user, Team $team): bool
    {
        return $user->can('organization.view') || $user->can('teams.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('teams.manage');
    }

    public function update(User $user, Team $team): bool
    {
        return $user->can('teams.manage');
    }

    public function delete(User $user, Team $team): bool
    {
        return $user->can('teams.manage');
    }

    public function restore(User $user, Team $team): bool
    {
        return $user->can('teams.manage');
    }

    public function forceDelete(User $user, Team $team): bool
    {
        return $user->hasRole('super-admin');
    }
}
