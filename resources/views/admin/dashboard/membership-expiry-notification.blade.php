@if(($membershipExpiringCount ?? 0) > 0)
<div class="alert alert-warning d-flex flex-wrap align-items-center gap-3 mb-4" role="status">
    <i class="bi bi-calendar-event fs-4" aria-hidden="true"></i>
    <div class="flex-grow-1">
        <strong class="d-block">{{ number_format($membershipExpiringCount) }} {{ $membershipExpiringCount === 1 ? 'membership expires' : 'memberships expire' }} within 7 days</strong>
        <span>Includes memberships expiring today in the selected site.</span>
    </div>
    <a class="btn btn-sm btn-outline-warning" href="{{ route('admin.members.membership-expiry', ['status' => 'expiring', 'days' => 7]) }}">View members <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i></a>
</div>
@endif
