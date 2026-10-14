@extends('admin.layout')
@section('title', 'Activation Requests')
@section('page-title', 'Activation Requests')
@section('content')
<div class="container-fluid py-4">
    <h1 class="h3">Activation Requests</h1>
    <p class="text-muted mb-4">Review the submitted settings before approving. Approval activates the profile and applies these settings.</p>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-nowrap">
                        <th scope="col">Profile</th>
                        <th scope="col">Requested By</th>
                        <th scope="col">Requested At</th>
                        <th scope="col">Plan</th>
                        <th scope="col">Start Date</th>
                        <th scope="col">Relationship Manager</th>
                        <th scope="col">Visibility</th>
                        <th scope="col" class="text-end">View Count</th>
                        <th scope="col" class="text-end">Wallet Addition</th>
                        <th scope="col">View Rates</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($requests as $activation)
                    @php
                        $settings = json_decode($activation->settings, true);
                        $ranges = collect($settings['ranges'] ?? [])->filter(fn ($range) => filled($range['range_from'] ?? null));
                    @endphp
                    <tr>
                        <td class="text-nowrap">
                            <a class="fw-semibold" href="{{ route('admin.members.show', $activation->member_id) }}">{{ $activation->profile_id }}</a>
                            <div class="small text-muted mt-1">{{ $activation->full_name }}</div>
                        </td>
                        <td class="text-nowrap">{{ $activation->requested_by_name }}</td>
                        <td class="text-nowrap">
                            {{ \Illuminate\Support\Carbon::parse($activation->created_at)->format('d M Y') }}
                            <div class="small text-muted mt-1">{{ \Illuminate\Support\Carbon::parse($activation->created_at)->format('h:i A') }}</div>
                        </td>
                        <td class="text-nowrap">{{ $settings['plan_id'] == 0 ? 'No Plan / Free' : (\Illuminate\Support\Facades\DB::connection('site')->table('membership_plans')->where('id', $settings['plan_id'])->value('plan_name') ?? 'Unavailable plan') }}</td>
                        <td class="text-nowrap">{{ \Illuminate\Support\Carbon::parse($settings['plan_activation_date'])->format('d M Y') }}</td>
                        <td class="text-nowrap">{{ \App\Models\Admin::find($settings['staff_id'])?->name ?? 'Unavailable staff' }}</td>
                        <td><span class="badge {{ $settings['profile_hide'] === 'Yes' ? 'text-bg-secondary' : 'text-bg-success' }}">{{ $settings['profile_hide'] === 'Yes' ? 'Hidden' : 'Visible' }}</span></td>
                        <td class="text-end">{{ number_format((int) $settings['profile_view_count']) }}</td>
                        <td class="text-end text-nowrap">₹{{ number_format((float) $settings['wallet_amount'], 2) }}</td>
                        <td class="text-nowrap">
                            @forelse($ranges as $range)
                                <div>{{ $range['range_from'] }}–{{ $range['range_to'] }}: ₹{{ number_format((float) $range['price'], 2) }}</div>
                            @empty
                                <span class="text-muted">No view rates</span>
                            @endforelse
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-2 align-items-start">
                                <form method="POST" action="{{ route('admin.members.activation.approve', $activation->id) }}" onsubmit="return confirm('Approve and activate this profile with the submitted settings?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success text-nowrap">Approve &amp; Activate</button>
                                </form>
                                <form method="POST" action="{{ route('admin.members.activation.reject', $activation->id) }}" onsubmit="return confirm('Reject this activation request?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Reject</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="11" class="text-center text-muted py-5">No pending activation requests.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $requests->links() }}</div>
</div>
@endsection
