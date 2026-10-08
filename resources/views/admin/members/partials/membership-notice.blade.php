@php
$membershipStatus = 'none';
$membershipStatusLabel = 'No Membership';
$membershipStatusClass = 'secondary';
$membershipDaysRemaining = null;
$membershipNoticeDate = null;
if ($membershipPlan && $membershipExpiryDate) {
    $membershipToday = \Carbon\CarbonImmutable::now('Asia/Kolkata')->startOfDay();
    $membershipNoticeDate = \Carbon\CarbonImmutable::parse($membershipExpiryDate->format('Y-m-d'), 'Asia/Kolkata')->startOfDay();
    $membershipDaysRemaining = (int) $membershipToday->diffInDays($membershipNoticeDate, false);
    $membershipStatus = $membershipDaysRemaining < 0 ? 'expired' : ($membershipDaysRemaining <= 7 ? 'expiring' : 'active');
    $membershipStatusLabel = ['expired' => 'Expired', 'expiring' => 'Expiring Soon', 'active' => 'Active'][$membershipStatus];
    $membershipStatusClass = ['expired' => 'danger', 'expiring' => 'warning', 'active' => 'success'][$membershipStatus];
}
@endphp

@if(in_array($membershipStatus, ['expired', 'expiring'], true))
<div class="alert alert-{{ $membershipStatusClass }} d-flex flex-wrap align-items-center gap-3 mb-4" role="status">
    <i class="bi bi-{{ $membershipStatus === 'expired' ? 'calendar-x' : 'calendar-event' }} fs-4" aria-hidden="true"></i>
    <div class="flex-grow-1">
        <strong class="d-block">{{ $membershipStatus === 'expired' ? 'Membership expired' : ($membershipDaysRemaining === 0 ? 'Membership expires today' : 'Membership expires in '.$membershipDaysRemaining.' '.($membershipDaysRemaining === 1 ? 'day' : 'days')) }}</strong>
        <span>{{ $membershipPlan->plan_name }} {{ $membershipStatus === 'expired' ? 'expired on' : 'is valid through' }} {{ $membershipNoticeDate->format('d M Y') }}.</span>
    </div>
    <a class="btn btn-sm btn-outline-{{ $membershipStatusClass }}" href="#membership-details">View membership <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i></a>
</div>
@endif
