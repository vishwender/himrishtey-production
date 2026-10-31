<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class MembershipExpiry
{
    /** Read-only expiry metadata for the authenticated member, using inclusive plan days. */
    public function forMember(\App\Models\Member $member): array
    {
        $empty = ['membership_expires_on' => null, 'membership_days_remaining' => null];
        if (! $member->plan_id || ! $member->plan_activation_date
            || strtolower(trim((string) $member->active)) === 'deleted') {
            return $empty;
        }

        $duration = (int) $member->getConnection()->table('membership_plans')
            ->where('id', $member->plan_id)->value('duration_days');
        if ($duration <= 0) {
            return $empty;
        }

        $date = substr((string) $member->plan_activation_date, 0, 10);
        try {
            $activation = CarbonImmutable::createFromFormat('!Y-m-d', $date, 'Asia/Kolkata');
        } catch (\Throwable) {
            return $empty;
        }
        $today = CarbonImmutable::now('Asia/Kolkata')->startOfDay();
        if (! $activation || $activation->format('Y-m-d') !== $date || $activation->greaterThan($today)) {
            return $empty;
        }
        $expiry = $activation->addDays($duration - 1);

        return [
            'membership_expires_on' => $expiry->toDateString(),
            'membership_days_remaining' => (int) $today->diffInDays($expiry, false),
        ];
    }

    public function query(int $days = 7, ?CarbonImmutable $today = null): Builder
    {
        $today ??= CarbonImmutable::now('Asia/Kolkata')->startOfDay();
        $deadline = $today->addDays($days);
        $db = DB::connection('site');
        $expirySql = $db->getDriverName() === 'sqlite'
            ? "date(m.plan_activation_date, '+' || (CAST(p.duration_days AS INTEGER) - 1) || ' days')"
            : 'DATE(DATE_ADD(m.plan_activation_date, INTERVAL (CAST(p.duration_days AS SIGNED) - 1) DAY))';

        $source = $db->table('members as m')
            ->join('membership_plans as p', 'p.id', '=', 'm.plan_id')
            ->leftJoin('membership_type as t', 't.id', '=', 'p.membership_type')
            ->where('p.duration_days', '>', 0)
            ->whereNotNull('m.plan_activation_date')
            ->where('m.plan_activation_date', '!=', '')
            ->where(function ($query) {
                $query->whereNull('m.active')->orWhereRaw("LOWER(TRIM(m.active)) != 'deleted'");
            })
            ->select([
                'm.id', 'm.profile_id', 'm.full_name', 'm.mobile_number', 'm.email', 'm.photo',
                'm.active', 'm.plan_activation_date', 'm.relationship_manager',
                'p.id as plan_id', 'p.plan_name', 'p.duration_days',
                'p.membership_type as type_id', 't.plan_name as type_name',
            ])->selectRaw($expirySql.' as expiry_date');

        return $db->query()->fromSub($source, 'memberships')
            ->whereDate('plan_activation_date', '<=', $today->toDateString())
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', $deadline->toDateString());
    }

    public function expiringCount(int $days = 7): int
    {
        $today = CarbonImmutable::now('Asia/Kolkata')->startOfDay();

        return $this->query($days, $today)->where('expiry_date', '>=', $today->toDateString())->count();
    }
}
