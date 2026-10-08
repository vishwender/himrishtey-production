@extends('admin.layout')
@section('title', 'Membership Expiry')
@section('page-title', 'Membership Expiry')
@section('content')
<div class="container-fluid members-page">
    <div class="members-page-header mb-4">
        <h1 class="h3 mb-1">Membership Expiry</h1>
        <p class="text-muted mb-0">Expired memberships and memberships ending within {{ $days }} days in the selected site.</p>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-sm-6"><div class="card p-3"><span class="text-muted">Expired</span><strong class="h3 mb-0 text-danger">{{ number_format($expiredCount) }}</strong></div></div>
        <div class="col-sm-6"><div class="card p-3"><span class="text-muted">Expiring within {{ $days }} days</span><strong class="h3 mb-0 text-warning">{{ number_format($expiringCount) }}</strong></div></div>
    </div>
    <div class="card mb-4"><div class="card-body">
        <form method="GET" action="{{ route('admin.members.membership-expiry') }}" class="row g-3">
            <div class="col-md-4"><label class="form-label" for="expiry-search">Search member</label><input class="form-control" id="expiry-search" name="search" value="{{ request('search') }}" placeholder="Profile ID, name, email or mobile"></div>
            <div class="col-md-4"><label class="form-label" for="expiry-status">Expiry status</label><select class="form-select" name="status" id="expiry-status">@foreach(['all' => 'Expired & expiring soon', 'expired' => 'Expired', 'expiring' => 'Expiring soon'] as $value => $label)<option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>@endforeach</select></div>
            <div class="col-md-4"><label class="form-label" for="expiry-days">Look ahead (days)</label><input class="form-control" id="expiry-days" name="days" type="number" min="1" max="365" value="{{ $days }}"></div>
            <div class="col-md-6"><label class="form-label" for="expiry-type">Membership type</label><select class="form-select" name="type_id" id="expiry-type"><option value="">All types</option>@foreach($types as $type)<option value="{{ $type->id }}" @selected((string) request('type_id') === (string) $type->id)>{{ $type->plan_name }}</option>@endforeach</select></div>
            <div class="col-md-6"><label class="form-label" for="expiry-plan">Activation plan</label><select class="form-select" name="plan_id" id="expiry-plan"><option value="">All plans</option>@foreach($plans as $plan)<option value="{{ $plan->id }}" @selected((string) request('plan_id') === (string) $plan->id)>{{ $plan->plan_name }}</option>@endforeach</select></div>
            <div class="col-12 d-flex flex-wrap gap-2"><button class="btn btn-primary" type="submit"><i class="bi bi-funnel me-1" aria-hidden="true"></i>Apply Filters</button><a class="btn btn-outline-secondary" href="{{ route('admin.members.membership-expiry') }}">Reset</a></div>
        </form>
    </div></div>
    <div class="card"><div class="card-body p-0"><div class="table-responsive">
        <table class="table table-hover align-middle mb-0 members-table">
            <thead class="table-light"><tr><th>Profile ID</th><th>Member</th><th>Mobile</th><th>Membership type</th><th>Activation plan</th><th>Activated</th><th>Expires</th><th>Expiry status</th><th>Relationship manager</th><th class="members-actions text-end">Profile</th></tr></thead>
            <tbody>@forelse($members as $member)
                @php
                $expiry = \Carbon\CarbonImmutable::parse($member->expiry_date, 'Asia/Kolkata');
                $remaining = (int) $today->diffInDays($expiry, false);
                @endphp
                <tr>
                    <td class="text-nowrap">{{ $member->profile_id }}</td><td>{{ $member->full_name }}</td><td class="text-nowrap">{{ $member->mobile_number ?: '—' }}</td><td>{{ $member->type_name ?: '—' }}</td><td>{{ $member->plan_name }}<small class="d-block text-muted">{{ $member->duration_days }} days</small></td>
                    <td class="text-nowrap">{{ \Carbon\CarbonImmutable::parse($member->plan_activation_date)->format('d M Y') }}</td><td class="text-nowrap">{{ $expiry->format('d M Y') }}</td>
                    <td><span class="badge {{ $remaining < 0 ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning' }}">{{ $remaining < 0 ? 'Expired '.abs($remaining).' days ago' : ($remaining === 0 ? 'Expires today' : $remaining.' days left') }}</span></td>
                    <td>{{ $member->relationship_manager ?: 'Unassigned' }}</td>
                    <td class="members-actions text-end">
                        @if(!auth('admin')->user()?->hasRole('content-manager') || auth('admin')->user()?->hasAnyRole(['super-admin', 'member-manager', 'relationship-manager']))
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.members.show', ['id' => $member->id, 'return' => request()->fullUrl()]) }}">View Profile</a>
                        @else <span class="text-muted">{{ $member->active ?: 'Inactive' }}</span> @endif
                    </td>
                </tr>
            @empty <tr><td colspan="10" class="text-center text-muted py-5">No memberships match these filters.</td></tr> @endforelse</tbody>
        </table>
    </div></div>
    <div class="card-footer d-flex flex-wrap justify-content-between align-items-center gap-3"><span class="text-muted small">{{ number_format($members->total()) }} memberships</span>{{ $members->links() }}</div></div>
    <p class="small text-muted mt-3">Expiry is activation date + plan duration − 1 day. Expiry-day memberships remain valid through that day. Profiles without a valid dated plan are excluded.</p>
</div>
@endsection
