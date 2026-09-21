@extends('layouts.public')

@section('title', 'Membership Plans - ' . $siteName)

@push('head')
<link rel="stylesheet" href="{{ asset('assets/css/public-pricing.css') }}?v={{ filemtime(public_path('assets/css/public-pricing.css')) }}">
@endpush

@section('content')
<section class="pricing-page" id="main-content">
    <div class="pricing-hero">
        <p class="pricing-kicker">Membership</p>
        <h1 class="pricing-title">Choose a plan that suits you</h1>
        <p class="pricing-intro">Compare {{ $siteName }} membership plans and unlock the features you need.</p>
    </div>
    <div class="wrap">
        <div class="pricing-grid">
            @forelse ($pricings as $plan)
            <div class="pricing-plan">
                <article class="pricing-card">
                    <div class="pricing-content">
                        <h2 class="pricing-name">{{ $plan->plan_name }}</h2>
                        <p class="pricing-price">₹{{ $plan->final_cost }}</p>
                        <p>{{ $plan->duration_days }} days · {{ $plan->view_contact }} contact views</p>
                        @if ($plan->plan_description)
                        <p>{{ $plan->plan_description }}</p>
                        @endif
                        <a class="public-cta public-cta-primary" href="{{ route('login-form') }}#register">Register to choose this plan</a>
                    </div>
                </article>
            </div>
            @empty
            <div class="pricing-empty">
                <article class="pricing-card">
                    <div class="pricing-content">
                        <p>Membership plans are currently unavailable. Please contact support for assistance.</p>
                    </div>
                </article>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection