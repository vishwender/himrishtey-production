<?php

namespace App\Services;

use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminActivityLogger
{
    public function log(
        string $action,
        ?string $description = null,
        ?string $module = null,
        ?int $memberId = null,
        ?string $subjectType = null,
        ?int $subjectId = null,
        array $metadata = []
    ): ?AdminActivityLog {
        $admin = Auth::guard('admin')->user();
        $attributes = [
            'admin_id' => $admin?->id,
            'site_id' => session('admin_site_id'),
            'action' => $action,
            'module' => $module,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'member_id' => $memberId,
            'description' => $description,
            'metadata' => array_merge($metadata, ['audit_event_id' => (string) Str::uuid()]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now()->toDateTimeString(),
        ];

        try {
            return AdminActivityLog::create($attributes);
        } catch (\Throwable $e) {
            report($e);
            $path = 'audit-outbox/'.$attributes['metadata']['audit_event_id'].'.json';
            if (! Storage::disk('local')->put($path, json_encode($attributes, JSON_THROW_ON_ERROR))) {
                throw new \RuntimeException('Unable to persist the staff audit record.', 0, $e);
            }
            Log::warning('Staff audit record queued for database recovery.', ['event_id' => $attributes['metadata']['audit_event_id']]);

            return null;
        }
    }
}
