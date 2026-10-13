@extends('admin.layout')
@section('title', 'Activation Requests')
@section('page-title', 'Activation Requests')
@section('content')
<div class="container-fluid py-4">
    <h1 class="h3">Activation Requests</h1>
    <p class="text-muted">Review the submitted settings before approving. Approval activates the profile and applies these settings.</p>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead><tr><th>Profile</th><th>Requested By</th><th>Requested At</th><th>Activation Settings</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($requests as $activation)
            @php
                $settings = json_decode($activation->settings, true);
            @endphp
            <tr>
                <td><a href="{{ route('admin.members.show', $activation->member_id) }}">{{ $activation->profile_id }}</a><br>{{ $activation->full_name }}</td>
                <td>{{ $activation->requested_by_name }}</td>
                <td>{{ $activation->created_at }}</td>
                <td>
                    <dl class="mb-0">
                        <dt>Plan / Start Date</dt><dd>{{ $settings['plan_id'] == 0 ? 'No Plan / Free' : (\Illuminate\Support\Facades\DB::connection('site')->table('membership_plans')->where('id', $settings['plan_id'])->value('plan_name') ?? 'Unavailable plan') }} / {{ $settings['plan_activation_date'] }}</dd>
                        <dt>Relationship Manager</dt><dd>{{ \App\Models\Admin::find($settings['staff_id'])?->name ?? 'Unavailable staff' }}</dd>
                        <dt>Visibility / View Count</dt><dd>{{ $settings['profile_hide'] === 'Yes' ? 'Hidden' : 'Visible' }} / {{ $settings['profile_view_count'] }}</dd>
                        <dt>Wallet Addition</dt><dd>₹{{ number_format((float) $settings['wallet_amount'], 2) }}</dd>
                        <dt>View Rates</dt><dd>
                        @foreach($settings['ranges'] ?? [] as $range)
                            @if(($range['range_from'] ?? null) !== null)
                            {{ $range['range_from'] }}–{{ $range['range_to'] }}: ₹{{ $range['price'] }}<br>
                            @endif
                        @endforeach
                        </dd>
                    </dl>
                </td>
                <td>
                    <form method="POST" action="{{ route('admin.members.activation.approve', $activation->id) }}" class="mb-2" onsubmit="return confirm('Approve and activate this profile with the submitted settings?')">@csrf<button class="btn btn-success">Approve &amp; Activate</button></form>
                    <form method="POST" action="{{ route('admin.members.activation.reject', $activation->id) }}" onsubmit="return confirm('Reject this activation request?')">@csrf<button class="btn btn-outline-danger">Reject</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted">No pending activation requests.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $requests->links() }}
</div>
@endsection
