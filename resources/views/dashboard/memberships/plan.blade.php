@extends('layouts.dashboard')

@section('title', 'Membership Plans - ' . $siteName)

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/memberships.css') }}?v={{ filemtime(public_path('assets/css/memberships.css')) }}">
@endsection

@section('content')
@php
    $plans = collect($data['plans']);
    $recommended = $plans->count() > 1
        ? $plans->filter(fn ($plan) => (float) $plan->final_cost > 0 && (int) $plan->view_contact > 0)
            ->sortByDesc(fn ($plan) => (int) $plan->view_contact / (float) $plan->final_cost)->first()
        : null;
    $formatPrice = fn ($value) => number_format((float) $value, (float) $value == floor((float) $value) ? 0 : 2);
@endphp
<section class="mp-page" aria-labelledby="plans-heading">
    <a class="mp-back" href="{{ route('memberships') }}"><i data-lucide="arrow-left" aria-hidden="true"></i> All memberships</a>
    <header class="mp-heading">
        <span class="mp-eyebrow"><i data-lucide="sparkles" aria-hidden="true"></i> YOUR NEXT CHAPTER</span>
        <h1 id="plans-heading">More possibilities.<br><span>More meaningful connections.</span></h1>
        <p>Explore {{ $data['membership']->plan_name }} plans and choose the space you need to find your person.</p>
    </header>

    <div class="mp-section-heading">
        <div><span class="mp-overline">COMPARE YOUR OPTIONS</span><h2>{{ $data['membership']->plan_name }}</h2></div>
        <span class="mp-detail"><i data-lucide="calendar-days" aria-hidden="true"></i> Validity shown for every plan</span>
    </div>

    <div class="mp-grid">
        @forelse($plans as $plan)
        @php
            $isRecommended = $recommended && $recommended->id == $plan->id;
            $saving = max(0, (float) $plan->plan_cost - (float) $plan->final_cost);
        @endphp
        <article class="mp-card {{ $isRecommended ? 'mp-card--recommended' : '' }}" aria-labelledby="plan-{{ $plan->id }}">
            <div class="mp-card-top">
                @include('dashboard.memberships.plan-icon', ['planName' => $plan->plan_name, 'icon' => $isRecommended ? 'sparkles' : 'heart'])
                @if($isRecommended)<span class="mp-badge">Recommended</span>@endif
            </div>
            <h3 id="plan-{{ $plan->id }}">{{ $plan->plan_name }}</h3>
            <p class="mp-validity">{{ number_format((int) $plan->duration_days) }} days to make a connection</p>
            <div class="mp-price"><span class="mp-currency">₹</span><strong>{{ $formatPrice($plan->final_cost ?? 0) }}</strong></div>
            <div class="mp-savings">
                @if($saving > 0)
                <span class="mp-original"><span class="visually-hidden">Original price </span><s>₹{{ $formatPrice($plan->plan_cost) }}</s></span>
                <span class="mp-saving">Save ₹{{ $formatPrice($saving) }}</span>
                @else
                <span>For the full plan duration</span>
                @endif
            </div>
            <div class="mp-contact-highlight"><strong>{{ number_format((int) $plan->view_contact) }}</strong><span>contact views<br><small>Take the next step</small></span><i data-lucide="contact-round" aria-hidden="true"></i></div>
            <ul class="mp-features">
                <li><i data-lucide="check" aria-hidden="true"></i><span><strong>{{ number_format((int) $plan->view_profile) }}</strong> profile views</span></li>
                <li><i data-lucide="check" aria-hidden="true"></i><span><strong>{{ number_format((int) $plan->view_contact) }}</strong> contact views</span></li>
                <li><i data-lucide="check" aria-hidden="true"></i><span><strong>{{ number_format((int) $plan->duration_days) }} days</strong> of membership</span></li>
            </ul>
            <a class="mp-button {{ $isRecommended ? 'mp-button--primary' : 'mp-button--outline' }}" href="{{ route('membership.checkout', $plan->id) }}">Choose {{ $plan->plan_name }}<i data-lucide="arrow-right" aria-hidden="true"></i></a>
            @if($isRecommended)<p class="mp-recommendation-note">Most contact views per rupee in this selection.</p>@endif
        </article>
        @empty
        <div class="mp-empty"><i data-lucide="calendar-heart" aria-hidden="true"></i><h3>New plans are on their way</h3><p>Please check back soon or request a callback for help.</p><a class="mp-button mp-button--primary" href="{{ route('memberships') }}">Explore memberships</a></div>
        @endforelse
    </div>

    <div class="mp-information">
        <section class="mp-information-card" aria-labelledby="membership-about"><span class="mp-small-icon"><i data-lucide="heart-handshake" aria-hidden="true"></i></span><div><h2 id="membership-about">A little more about your membership</h2><p>{{ $data['membership']->plan_description ?: 'Compare the contact views, profile views and validity above to find the right fit.' }}</p></div></section>
        @if(filled($data['membership']->terms_and_conditions))
        <details class="mp-terms"><summary>Membership terms &amp; conditions<i data-lucide="chevron-down" aria-hidden="true"></i></summary><div>{!! nl2br(e($data['membership']->terms_and_conditions)) !!}</div></details>
        @endif
    </div>
</section>
@endsection
