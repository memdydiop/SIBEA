<?php

namespace App\Actions\User;

use App\Actions\Audit\LogAuditAction;
use App\Models\User;

class DeactivateUserAction
{
    public function __construct(private readonly LogAuditAction $logAudit) {}

    public function __invoke(User $user): User
    {
        $oldValues = ['is_active' => $user->is_active];

        $user->update(['is_active' => false]);

        ($this->logAudit)('USER_DEACTIVATED', $user, $oldValues, ['is_active' => false]);

        return $user->refresh();
    }
}
