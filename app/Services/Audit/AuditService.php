<?php

namespace App\Services\Audit;

use App\Models\AuditLog;
use App\Models\User;

class AuditService
{
    /**
     * Log an audit event.
     */
    public function log(
        ?User $user,
        string $action,
        string $entityType,
        int $entityId,
        array $metadata = []
    ): void {
        AuditLog::create([
            'user_id' => $user?->id,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'metadata' => $metadata,
        ]);
    }
}
