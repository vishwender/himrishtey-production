<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MembershipExpiryController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'status' => ['nullable', Rule::in(['all', 'expired', 'expiring'])],
            'days' => ['nullable', 'integer', 'min:1', 'max:365'],
            'search' => ['nullable', 'string', 'max:255'],
            'plan_id' => ['nullable', 'integer', 'min:1'],
            'type_id' => ['nullable', 'integer', 'min:1'],
        ]);
        $days = (int) ($filters['days'] ?? 7);
        $status = $filters['status'] ?? 'all';
        $today = CarbonImmutable::now('Asia/Kolkata')->startOfDay();
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

        $query = $db->query()->fromSub($source, 'memberships')
            ->whereDate('plan_activation_date', '<=', $today->toDateString())
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', $deadline->toDateString());
        if (! empty($filters['plan_id'])) {
            $query->where('plan_id', $filters['plan_id']);
        }
        if (! empty($filters['type_id'])) {
            $query->where('type_id', $filters['type_id']);
        }
        if ($search = trim($filters['search'] ?? '')) {
            $query->where(function ($query) use ($search) {
                foreach (['profile_id', 'full_name', 'mobile_number', 'email'] as $column) {
                    $query->orWhere($column, 'like', '%'.$search.'%');
                }
            });
        }
        $expiredCount = (clone $query)->where('expiry_date', '<', $today->toDateString())->count();
        $expiringCount = (clone $query)->where('expiry_date', '>=', $today->toDateString())->count();
        if ($status === 'expired') {
            $query->where('expiry_date', '<', $today->toDateString());
        } elseif ($status === 'expiring') {
            $query->where('expiry_date', '>=', $today->toDateString());
        }
        $members = $query->orderBy('expiry_date')->orderBy('id')->paginate(25)->withQueryString();
        $plans = $db->table('membership_plans')->orderBy('plan_name')->get(['id', 'plan_name']);
        $types = $db->table('membership_type')->orderBy('plan_name')->get(['id', 'plan_name']);

        return view('admin.members.membership-expiry', compact(
            'members', 'plans', 'types', 'today', 'days', 'status', 'expiredCount', 'expiringCount'
        ));
    }
}
