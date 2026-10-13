<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\MemberRotation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MemberLogs
{
    public function attribution(Collection $actions, mixed $planId): array
    {
        $label = static function ($action): ?string {
            if (!$action) {
                return null;
            }
            $name = $action->metadata['admin_name'] ?? $action->admin?->name;

            return filled($name) ? 'Admin '.$name : 'Admin (name unavailable)';
        };

        return [
            'created_by' => $label($actions->where('action', 'member_created')->last()),
            'activated_by' => $label($actions->firstWhere('action', 'member_activated')),
            'plan_created_by' => $label($actions->first(fn ($action) =>
                isset($action->metadata['plan_id']) && (string) $action->metadata['plan_id'] === (string) $planId
            )),
        ];
    }

    public function record(int $memberId, string $type, string $text, ?string $date = null): ?int
    {
        $db = DB::connection('site');
        if (! $db->getSchemaBuilder()->hasTable('member_logs')) {
            return null;
        }

        return $db->table('member_logs')->insertGetId([
            'member_id' => $memberId, 'skey' => $type, 'purpose' => $text,
            'user' => auth('admin')->user()?->name ?? '',
            'assigned_date' => $date ?? now()->format('Y-m-d H:i:s'),
        ]);
    }

    public function history(int $memberId, Collection $auditRemarks): Collection
    {
        $db = DB::connection('site');
        $legacy = $db->getSchemaBuilder()->hasTable('member_logs')
            ? $db->table('member_logs')->where('member_id', $memberId)->get()
            : collect();
        $entries = $legacy->map(fn ($log) => [
            'id' => $log->id, 'type' => $log->skey, 'text' => $log->purpose,
            'staff' => $log->user ?? null, 'date' => $log->assigned_date,
        ]);
        foreach ($auditRemarks as $audit) {
            $metadata = $audit->metadata ?? [];
            if (isset($metadata['member_log_id']) && $legacy->contains('id', $metadata['member_log_id'])) {
                continue;
            }
            $entries->push([
                'type' => ($metadata['remark_type'] ?? '') === 'Added Remarks' ? 'Assigned Remarks' : ($metadata['remark_type'] ?? 'Remark'),
                'text' => $metadata['new_remarks'] ?? '', 'staff' => $audit->admin?->name,
                'date' => $audit->created_at?->format('Y-m-d H:i:s'),
            ]);
        }

        return $entries->sortByDesc('date')->values();
    }

    public function rotations(int $memberId, Collection $history): Collection
    {
        $entries = $history->where('type', 'Rotation Added')->values();
        if (! DB::connection('site')->getSchemaBuilder()->hasTable('member_rotations')) {
            return $entries;
        }
        $rotations = MemberRotation::where('member_id', $memberId)->get();
        $admins = $rotations->isEmpty() ? collect() : Admin::whereIn('id', $rotations->pluck('admin_id')->filter())->pluck('name', 'id');
        foreach ($rotations as $rotation) {
            $text = "Rotation #{$rotation->id} added";
            if (! $entries->contains('text', $text)) {
                $entries->push([
                    'type' => 'Rotation Added', 'text' => $text,
                    'staff' => $admins->get($rotation->admin_id) ?? 'Staff member unavailable',
                    'date' => $rotation->created_at?->format('Y-m-d H:i:s'),
                ]);
            }
        }

        return $entries->sortByDesc('date')->values();
    }

    public function walletCredits(int $memberId): Collection
    {
        $db = DB::connection('site');
        if (! $db->getSchemaBuilder()->hasTable('member_wallet')) {
            return collect();
        }

        return $db->table('member_wallet')->where('member_id', $memberId)
            ->where('amount_added', '>', 0)->orderByDesc('id')->get();
    }
}
