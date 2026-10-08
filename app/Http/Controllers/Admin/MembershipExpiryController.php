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
        $db = DB::connection('site');
        $query = app(\App\Services\MembershipExpiry::class)->query($days, $today);
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
