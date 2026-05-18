<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserAuditLog;
use Illuminate\Http\Request;

class UserAuditLogger
{
    /**
     * @param  array<string, mixed>  $metadata
     */
    public function record(
        ?User $user,
        string $activity,
        string $module,
        string $description,
        string $status = 'success',
        array $metadata = []
    ): UserAuditLog {
        /** @var Request|null $request */
        $request = app()->bound('request') ? request() : null;

        return UserAuditLog::query()->create([
            'user_id' => $user?->getKey(),
            'user_name' => $user?->name,
            'user_email' => $user?->email,
            'activity' => $activity,
            'module' => $module,
            'description' => $description,
            'status' => $status,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'metadata' => $metadata === [] ? null : $metadata,
        ]);
    }
}
