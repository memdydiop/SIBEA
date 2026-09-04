<?php

namespace App\Actions\Audit;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class LogAuditAction
{
    /**
     * Log an audit event.
     *
     * @param  array<string, mixed>|null  $oldValues
     * @param  array<string, mixed>|null  $newValues
     */
    public function __invoke(
        string $action,
        Model|string|null $auditable = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?int $userId = null,
    ): AuditLog {
        $auditableType = null;
        $auditableId = null;

        if ($auditable instanceof Model) {
            $auditableType = $auditable->getMorphClass();
            $auditableId = $auditable->getKey();
        } elseif (is_string($auditable)) {
            $auditableType = $auditable;
        }

        return AuditLog::create([
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'auditable_type' => $auditableType,
            'auditable_id' => $auditableId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
