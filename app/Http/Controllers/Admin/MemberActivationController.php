<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\SiteMember;
use App\Services\AdminActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MemberActivationController extends Controller
{
    private function authorizeActivation(): void
    {
        abort_unless(auth('admin')->user()?->hasAnyPermission([
            'manage-member-status',
            'edit-member',
            'edit-members',
        ]), 403);
    }

    private function ensureEligible(SiteMember $member): void
    {
        abort_if(in_array($member->active, ['Banned', 'deleted'], true), 403, 'This profile cannot be activated here.');
        abort_if($member->active === 'Yes', 409, 'This member is already active.');
    }

    public function create(int $id): View
    {
        $this->authorizeActivation();
        $member = SiteMember::findOrFail($id);
        $this->ensureEligible($member);
        $db = DB::connection('site');

        return view('admin.members.activate', [
            'pendingRequest' => $db->table('member_activation_requests')->where('member_id', $id)->where('status', 'pending')->first(),
            'member' => $member,
            'plans' => $db->table('membership_plans')->orderBy('plan_name')->get(),
            'staff' => Admin::query()->where('status', true)->whereNotNull('name')->where('name', '<>', '')->orderBy('name')->get(),
            'ranges' => $db->table('member_profile_range')->where('member_id', $id)->orderByRaw('CAST(range_from AS UNSIGNED)')->get(),
            'walletBalance' => $db->table('member_wallet')->where('member_id', $id)->latest('id')->value('wallet_balance') ?? 0,
        ]);
    }

    public function index(): View
    {
        abort_unless(auth('admin')->user()?->hasRole('super-admin'), 403);

        return view('admin.members.activation-requests', [
            'requests' => DB::connection('site')->table('member_activation_requests as requests')
                ->join('members', 'members.id', '=', 'requests.member_id')
                ->where('requests.status', 'pending')
                ->select('requests.*', 'members.profile_id', 'members.full_name')
                ->orderBy('requests.id')->paginate(25),
        ]);
    }

    public function approve(int $id): RedirectResponse
    {
        abort_unless(auth('admin')->user()?->hasRole('super-admin'), 403);
        $activation = DB::connection('site')->table('member_activation_requests')->find($id);
        abort_unless($activation, 404);
        abort_unless($activation->status === 'pending', 409, 'This request has already been reviewed.');
        $request = Request::create('/', 'POST', json_decode($activation->settings, true, 512, JSON_THROW_ON_ERROR));
        $request->attributes->set('activation_request_id', $id);

        $request->attributes->set('activation_requested_by', $activation->requested_by_name);

        return $this->store($request, (int) $activation->member_id);
    }

    public function reject(int $id): RedirectResponse
    {
        abort_unless(auth('admin')->user()?->hasRole('super-admin'), 403);
        $activation = DB::connection('site')->table('member_activation_requests')->find($id);
        abort_unless($activation, 404);
        $updated = DB::connection('site')->table('member_activation_requests')
            ->where('id', $id)->where('status', 'pending')->update([
                'status' => 'rejected', 'reviewed_by' => auth('admin')->id(),
                'reviewed_at' => now(), 'updated_at' => now(),
            ]);
        abort_unless($updated, 409, 'This request has already been reviewed or does not exist.');

        app(AdminActivityLogger::class)->log(
            action: 'member_activation_rejected', description: "Rejected activation request #{$id} raised by {$activation->requested_by_name}.",
            module: 'members', memberId: (int) $activation->member_id,
            subjectType: 'member', subjectId: (int) $activation->member_id,
            metadata: ['activation_request_id' => $id, 'requested_by' => $activation->requested_by, 'requested_by_name' => $activation->requested_by_name, 'profile_id' => SiteMember::find($activation->member_id)?->profile_id, 'status' => 'rejected'],
        );

        return back()->with('success', 'Activation request rejected.');
    }

    public function store(Request $request, int $id): RedirectResponse
    {
        $this->authorizeActivation();
        $this->ensureEligible(SiteMember::findOrFail($id));
        $data = $request->validate([
            'plan_id' => ['required', 'integer', 'min:0'],
            'plan_activation_date' => ['required', 'date_format:Y-m-d'],
            'staff_id' => ['required', 'integer', 'min:0'],
            'profile_hide' => ['required', Rule::in(['Yes', 'No'])],
            'profile_view_count' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'wallet_amount' => ['required', 'numeric', 'min:0', 'max:1000000', 'decimal:0,2'],
            'ranges' => ['nullable', 'array', 'max:20'],
            'ranges.*.range_from' => ['nullable', 'integer', 'min:1', 'max:2147483647'],
            'ranges.*.range_to' => ['nullable', 'integer', 'min:1', 'max:2147483647'],
            'ranges.*.price' => ['nullable', 'numeric', 'min:0', 'max:1000000', 'decimal:0,2'],
        ]);
        $staff = Admin::query()->where('status', true)->whereNotNull('name')->where('name', '<>', '')->find($data['staff_id']);
        if (! $staff) {
            throw ValidationException::withMessages(['staff_id' => 'Select an active staff user.']);
        }
        $db = DB::connection('site');
        if ((int) $data['plan_id'] !== 0 && ! $db->table('membership_plans')->where('id', $data['plan_id'])->exists()) {
            throw ValidationException::withMessages(['plan_id' => 'Select a valid membership plan.']);
        }
        $submittedRanges = collect($data['ranges'] ?? []);
        foreach ($submittedRanges as $index => $range) {
            $filledValues = collect(['range_from', 'range_to', 'price'])
                ->filter(fn (string $field) => ($range[$field] ?? '') !== '')
                ->count();
            if ($filledValues > 0 && $filledValues < 3) {
                throw ValidationException::withMessages([
                    "ranges.$index.range_from" => 'Complete the From, To, and Price fields for this range.',
                ]);
            }
        }
        $ranges = $submittedRanges
            ->filter(fn (array $range) => collect($range)->contains(fn ($value) => $value !== null && $value !== ''))
            ->sortBy('range_from')->values();
        $previousEnd = 0;
        foreach ($ranges as $range) {
            if ($range['range_from'] > $range['range_to'] || $range['range_from'] <= $previousEnd) {
                throw ValidationException::withMessages(['ranges' => 'Ranges must have a valid start and end and must not overlap.']);
            }
            $previousEnd = (int) $range['range_to'];
        }

        if (! auth('admin')->user()->hasRole('super-admin')) {
            $activation = $db->transaction(function () use ($db, $id, $data) {
                $member = SiteMember::query()->whereKey($id)->lockForUpdate()->firstOrFail();
                $this->ensureEligible($member);
                abort_if($db->table('member_activation_requests')->where('member_id', $id)->where('status', 'pending')->exists(), 409, 'An activation request is already pending for this profile.');
                $requestId = $db->table('member_activation_requests')->insertGetId([
                    'member_id' => $id, 'requested_by' => auth('admin')->id(),
                    'requested_by_name' => auth('admin')->user()->name,
                    'settings' => json_encode($data, JSON_THROW_ON_ERROR), 'status' => 'pending',
                    'created_at' => now(), 'updated_at' => now(),
                ]);

                return ['id' => $requestId, 'profile_id' => $member->profile_id];
            });
            app(AdminActivityLogger::class)->log(
                action: 'member_activation_requested', description: "Requested activation of {$activation['profile_id']} (request #{$activation['id']}); awaiting Super Admin approval.",
                module: 'members', memberId: $id, subjectType: 'member', subjectId: $id,
                metadata: ['activation_request_id' => $activation['id'], 'profile_id' => $activation['profile_id'], 'status' => 'pending', 'settings' => $data],
            );

            return redirect()->route('admin.members.show', $id)->with('success', 'Activation request submitted. The profile will activate after Super Admin approval.');
        }

        $lockName = 'member-activation-'.sha1($db->getDatabaseName());
        $usesAdvisoryLock = in_array($db->getDriverName(), ['mysql', 'mariadb'], true);
        if ($usesAdvisoryLock) {
            abort_unless((int) $db->selectOne('SELECT GET_LOCK(?, 10) AS acquired', [$lockName])->acquired === 1, 409, 'Another activation is being saved. Please try again.');
        }
        try {
            $member = $db->transaction(function () use ($id, $db, $data, $ranges, $staff, $request) {
                $member = SiteMember::query()->whereKey($id)->lockForUpdate()->firstOrFail();
                $this->ensureEligible($member);
                $pending = $db->table('member_activation_requests')->where('member_id', $id)->where('status', 'pending')->lockForUpdate()->first();
                $requestId = $request->attributes->get('activation_request_id');
                if ($requestId) {
                    abort_unless($pending && (int) $pending->id === (int) $requestId, 409, 'This request has already been reviewed.');
                } else {
                    abort_if($pending, 409, 'Approve the pending activation request from Activation Requests.');
                }
                $number = (int) $member->activation_number;
                if (! $number || (string) $member->plan_activation_date !== $data['plan_activation_date']) {
                    $number = (int) $db->table('members')->max('activation_number') + 1;
                }
                $member->update([
                    'active' => 'Yes',
                    'activation_number' => $number,
                    'plan_id' => $data['plan_id'],
                    'plan_activation_date' => $data['plan_activation_date'],
                    'relationship_manager' => $staff->name,
                    'profile_hide' => $data['profile_hide'] === 'Yes' ? 'Yes' : '',
                    'profile_view_count' => $data['profile_view_count'],
                ]);
                $db->table('member_profile_range')->where('member_id', $id)->delete();
                if ($ranges->isNotEmpty()) {
                    $db->table('member_profile_range')->insert($ranges->map(fn ($range) => [
                        'member_id' => $id,
                        'range_from' => $range['range_from'],
                        'range_to' => $range['range_to'],
                        'price' => $range['price'],
                    ])->all());
                }

                if ((float) $data['wallet_amount'] > 0) {
                    $balance = $db->table('member_wallet')->where('member_id', $id)->latest('id')->lockForUpdate()->value('wallet_balance') ?? 0;
                    $db->table('member_wallet')->insert([
                        'member_id' => $id,
                        'wallet_balance' => number_format((float) $balance + (float) $data['wallet_amount'], 2, '.', ''),
                        'amount_added' => $data['wallet_amount'],
                        'amount_deducted' => '0',
                        'added_by' => auth('admin')->user()->name,
                        'created_at' => now(),
                        'update_at' => now(),
                    ]);
                }
                if (! $db->table('member_logs')->where('member_id', $id)->where('skey', 'Relationship Manager')->where('purpose', $staff->name)->exists()) {
                    $db->table('member_logs')->insert([
                        'member_id' => $id, 'skey' => 'Relationship Manager',
                        'purpose' => $staff->name, 'user' => $staff->name,
                        'assigned_date' => now()->format('Y-m-d H:i:s'),
                    ]);
                }

                if ($requestId) {
                    $db->table('member_activation_requests')->where('id', $requestId)->update([
                        'status' => 'approved', 'reviewed_by' => auth('admin')->id(),
                        'reviewed_at' => now(), 'updated_at' => now(),
                    ]);
                }

                return $member;
            });
        } finally {
            if ($usesAdvisoryLock) {
                $db->selectOne('SELECT RELEASE_LOCK(?)', [$lockName]);
            }
        }
        app(AdminActivityLogger::class)->log(
            action: 'member_activated', description: "Activated {$member->profile_id} and assigned {$staff->name}.",
            module: 'members', memberId: $id, subjectType: 'member', subjectId: $id,
            metadata: ['profile_id' => $member->profile_id, 'activation_request_id' => $request->attributes->get('activation_request_id'), 'activation_number' => $member->activation_number, 'relationship_manager' => $staff->name, 'plan_id' => $data['plan_id'], 'wallet_amount' => $data['wallet_amount']],
        );

        if ($requestId = $request->attributes->get('activation_request_id')) {
            app(AdminActivityLogger::class)->log(
                action: 'member_activation_approved',
                description: "Approved activation request #{$requestId} for {$member->profile_id} raised by {$request->attributes->get('activation_requested_by')}; profile activated.",
                module: 'members', memberId: $id, subjectType: 'member', subjectId: $id,
                metadata: ['activation_request_id' => $requestId, 'profile_id' => $member->profile_id, 'requested_by_name' => $request->attributes->get('activation_requested_by'), 'status' => 'approved'],
            );
        }

        return redirect()->route('admin.members.show', $id)->with('success', 'Member activated and activation settings saved successfully.');
    }
}
