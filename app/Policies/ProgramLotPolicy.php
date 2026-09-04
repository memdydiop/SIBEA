<?php

namespace App\Policies;

use App\Models\ProgramLot;
use App\Models\User;

class ProgramLotPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('cms.manage');
    }

    public function view(User $user, ProgramLot $programLot): bool
    {
        return $user->can('cms.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('cms.manage');
    }

    public function update(User $user, ProgramLot $programLot): bool
    {
        return $user->can('cms.manage');
    }

    public function delete(User $user, ProgramLot $programLot): bool
    {
        return $user->can('cms.manage');
    }

    public function restore(User $user, ProgramLot $programLot): bool
    {
        return $user->can('cms.manage');
    }

    public function forceDelete(User $user, ProgramLot $programLot): bool
    {
        return $user->hasRole('super-admin');
    }
}
