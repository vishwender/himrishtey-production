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

                        <h2 class="pricing-name">
                            {{ $plan->plan_name }}
                        </h2>
                        @php
                        $originalPrices = [
                        'silver' => 2500,
                        'silver+' => 3700,
                        'gold' => 3100,
                        'gold+' => 5100,
                        ];

                        $planKey = strtolower(trim($plan->plan_name));
                        $originalPrice = $originalPrices[$planKey] ?? null;
                        @endphp

                        <div class="pricing-price-wrap">

                            {{-- Current Price --}}
                            <p class="pricing-price">
                                ₹{{ (float) $plan->final_cost > 0
            ? number_format($plan->final_cost, 0)
            : '0'
        }}
                            </p>

                            {{-- Original / Strikethrough Price --}}
                            @if ($originalPrice)
                            <p class="pricing-original-price">
                                ₹{{ number_format($originalPrice) }}
                            </p>
                            @endif

                        </div>

                        <p>
                            {{ $plan->duration_days }} days ·
                            {{ $plan->view_contact }} contact views
                        </p>

                        @if ($plan->plan_description)
                        <p>{{ $plan->plan_description }}</p>
                        @endif

                        <a
                            class="public-cta public-cta-primary"
                            href="{{ route('login-form') }}#register">
                            Register to choose this plan
                        </a>

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
<style>
    .pricing-price-wrap {
        margin: 16px 0 12px;
    }

    .pricing-price {
        margin: 0;
        font-size: 2.4rem;
        font-weight: 800;
        line-height: 1.1;
    }

    .pricing-original-price {
        margin: 6px 0 0;
        font-size: 1rem;
        color: #8a8f98;
        text-decoration: line-through;
        text-decoration-thickness: 1.5px;
    }
</style>
@endsection