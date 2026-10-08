<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class MembershipExpiry
{
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
                'm.id', 'm.profile_id', 'm.full_name', 'm.mobile_number', 'm.email',
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
