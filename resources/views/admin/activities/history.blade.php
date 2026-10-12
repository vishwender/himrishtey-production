@extends('admin.layout')
@section('title', 'Member Activity History')
@section('page-title', 'Member Activity History')
@section('content')
<div class="container-fluid py-4">
    <h1 class="h3 mb-1">Activity History</h1>
    <p>{{ $member->full_name }} · {{ $member->profile_id }}</p>
    @if($historyUnavailable ?? false)
    <div class="alert alert-warning" role="alert">Activity history is not enabled for this site yet. The site's database migrations must be applied before new events can be recorded.</div>
    @endif
    <p class="text-muted">Changes recorded since activity history was enabled. Removed items remain in this history.</p>
    <a class="btn btn-outline-secondary mb-3" href="{{ route('admin.activities.member', $member->id) }}">Back to Member Activity</a>
    <div class="card table-responsive"><table class="table mb-0"><thead><tr><th>Activity</th><th>Change</th><th>Direction</th><th>Related member</th><th>Details</th><th>Date</th></tr></thead><tbody>
    @forelse($data as $row)
        @php($details = json_decode($row->metadata ?? '{}', true) ?? [])
        <tr><td>{{ ucwords(str_replace('_', ' ', $row->source)) }}</td><td>{{ ucfirst($row->event) }}</td><td>{{ (int) $row->member_id === (int) $member->id ? 'Outgoing' : 'Incoming' }}</td><td>{{ (int) $row->member_id === (int) $member->id ? ($row->target_profile_id ?? $row->target_member_id ?? '—') : $row->member_id }}</td><td>@if(isset($details['status']))Status: {{ $details['status'] }}@elseif(isset($details['amount']))Amount: ₹{{ $details['amount'] }}@else — @endif</td><td>{{ $row->created_at }}</td></tr>
    @empty
        <tr><td colspan="6" class="text-center py-4">No historical events recorded yet.</td></tr>
    @endforelse
    </tbody></table></div>
    <div class="mt-3">{{ $data->links() }}</div>
</div>
@endsection
