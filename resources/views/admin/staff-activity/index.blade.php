@extends('admin.layout')
@section('title', 'Staff Activity')
@section('content')
<div class="container-fluid py-4">
    <h1>Staff Activity</h1>
    <p class="text-muted">Audit records across sites.</p>
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3"><label for="activity-search" class="form-label">Search</label><input id="activity-search" class="form-control" name="search" value="{{ request('search') }}" placeholder="Action, description or member ID"></div>
        <div class="col-md-3"><label for="activity-staff" class="form-label">Staff</label><select id="activity-staff" name="admin_id" class="form-select"><option value="">All staff</option>@foreach($admins as $staff)<option value="{{ $staff->id }}" @selected((string) request('admin_id') === (string) $staff->id)>{{ $staff->name }}</option>@endforeach</select></div>
        <div class="col-md-3"><label for="activity-site" class="form-label">Site</label><select id="activity-site" name="site_id" class="form-select"><option value="">All sites</option>@foreach($sites as $site)<option value="{{ $site->id }}" @selected((string) request('site_id') === (string) $site->id)>{{ $site->name }}</option>@endforeach</select></div>
        <div class="col-md-3"><label for="activity-action" class="form-label">Action</label><select id="activity-action" name="action" class="form-select"><option value="">All actions</option>@foreach($actions as $action)<option value="{{ $action }}" @selected(request('action') === $action)>{{ ucwords(str_replace('_', ' ', $action)) }}</option>@endforeach</select></div>
        <div class="col-md-3"><label for="activity-from" class="form-label">From</label><input id="activity-from" type="date" name="date_from" class="form-control" value="{{ request('date_from') }}"></div>
        <div class="col-md-3"><label for="activity-to" class="form-label">To</label><input id="activity-to" type="date" name="date_to" class="form-control" value="{{ request('date_to') }}"></div>
        <div class="col-md-6 d-flex align-items-end gap-2"><button class="btn btn-primary">Filter</button><a href="{{ route('admin.staff-activity.index') }}" class="btn btn-outline-secondary">Reset</a></div>
    </form>
    <div class="card table-responsive"><table class="table mb-0"><thead><tr><th>Staff</th><th>Action</th><th>Details</th><th>Member</th><th>Site</th><th>IP</th><th>Date</th></tr></thead><tbody>
    @forelse($activities as $activity)
        <tr>
            <td>@if($activity->admin)<a href="{{ route('admin.staff.activity', $activity->admin_id) }}">{{ $activity->admin->name }}</a>@else Deleted staff @endif</td>
            <td>{{ ucwords(str_replace('_', ' ', $activity->action)) }}</td>
            <td>{{ $activity->description ?: '—' }}@if(!empty($activity->metadata['fields']))<div class="small text-muted">Fields: {{ implode(', ', $activity->metadata['fields']) }}</div>@endif</td>
            <td>{{ $activity->member_id ?? '—' }}</td><td>{{ $activity->site?->name ?? '—' }}</td><td>{{ $activity->ip_address ?? '—' }}</td><td>{{ $activity->created_at?->format('d M Y h:i A') }}</td>
        </tr>
    @empty
        <tr><td colspan="7" class="text-center py-4">No activity found.</td></tr>
    @endforelse
    </tbody></table></div>
    <div class="mt-3">{{ $activities->links() }}</div>
</div>
@endsection
