<?php

namespace App\Policies;

use App\Models\QuoteRequest;
use App\Models\User;

class QuoteRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('cms.manage');
    }

    public function view(User $user, QuoteRequest $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('cms.manage');
    }

    public function update(User $user, QuoteRequest $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function delete(User $user, QuoteRequest $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function restore(User $user, QuoteRequest $modelInstance): bool
    {
        return $user->can('cms.manage');
    }

    public function forceDelete(User $user, QuoteRequest $modelInstance): bool
    {
        return $user->hasRole('super-admin');
    }
}
