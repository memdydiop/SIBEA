<?php

namespace App\Policies;

use App\Models\Testimonial;
use App\Models\User;

class TestimonialPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('cms.manage');
    }

    public function view(User $user, Testimonial $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('cms.manage');
    }

    public function update(User $user, Testimonial $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function delete(User $user, Testimonial $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function restore(User $user, Testimonial $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function forceDelete(User $user, Testimonial $modelInstance): bool
    {
        return $user->hasRole('super-admin');
    }
}
