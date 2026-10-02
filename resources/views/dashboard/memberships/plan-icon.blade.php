@php
    $iconStyle = match (strtolower(preg_replace('/\s+/', '', (string) $planName))) {
        'gold', 'gold+' => 'mp-plan-icon--gold',
        'silver', 'silver+' => 'mp-plan-icon--silver',
        default => '',
    };
@endphp
<span class="mp-plan-icon {{ $iconStyle }}"><i data-lucide="{{ $icon ?? 'heart' }}" aria-hidden="true"></i></span>
