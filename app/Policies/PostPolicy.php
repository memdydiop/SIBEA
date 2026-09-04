<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('cms.manage');
    }

    public function view(User $user, Post $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('cms.manage');
    }

    public function update(User $user, Post $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function delete(User $user, Post $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function restore(User $user, Post $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function forceDelete(User $user, Post $modelInstance): bool
    {
        return $user->hasRole('super-admin');
    }
}
