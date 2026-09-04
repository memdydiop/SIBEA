<?php

namespace App\Actions\User;

use App\Actions\Audit\LogAuditAction;
use App\Models\User;

class ActivateUserAction
{
    public function __construct(private readonly LogAuditAction $logAudit) {}

    public function __invoke(User $user): User
    {
        $oldValues = ['is_active' => $user->is_active];

        $user->update(['is_active' => true]);

        ($this->logAudit)('USER_ACTIVATED', $user, $oldValues, ['is_active' => true]);

        return $user->refresh();
    }
}
