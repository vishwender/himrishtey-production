@extends('layouts.dashboard')

@section('title', 'Buy Plan - Himrishtey')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/memberships.css') }}?v={{ filemtime(public_path('assets/css/memberships.css')) }}">
@endsection

@section('content')

<main class="membership-details">

    <!-- Membership Header -->
    <div class="page-header">
        <h1>{{ $data['membership']->plan_name }}</h1>
    </div>

    @php
    $colors = [
    'silver' => 'silver-card',
    'gold' => 'gold-card',
    'gold+' =>'gold-card',
    'platinum' => 'platinum-card',
    'diamond' => 'diamond-card',
    ];
    @endphp

    <section class="pricing-grid">

        @foreach($data['plans'] as $plan)

        @php
        $cardClass = $colors[strtolower($plan->plan_name)] ?? 'default-card';
        @endphp

        <article class="pricing-card {{ $cardClass }}">

            <span class="discount">
                {{ $plan->discount_percentage }}% OFF
            </span>

            <div class="plan-header">

                <h2>{{ strtoupper($plan->plan_name) }}</h2>

                <p class="allowed-contact">
                    Allowed Contacts :
                    <strong>{{ $plan->view_contact }}</strong>
                </p>

            </div>

            <div class="price-block">

                <div class="plan-price">

                    <span class="currency">₹</span>

                    <span class="amount">
                        {{ number_format((float) ($plan->final_cost ?? 0)) }}
                    </span>

                    <span class="duration">
                        / {{ $plan->duration_days }} Days
                    </span>

                </div>

                <div class="old-price">
                    ₹{{ number_format($plan->plan_cost) }}
                </div>

            </div>

            <ul class="plan-features">

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    {{ number_format($plan->view_profile) }} Profile Views
                </li>

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    {{ $plan->view_contact }} Contact Views
                </li>

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    {{ $plan->duration_days }} Days Validity
                </li>

            </ul>
            <form action="{{ route('membership.checkout', $plan->id) }}" method="GET">
                @csrf
                <button type="submit" class="buy-btn">
                    Buy Now
                </button>
            </form>

        </article>

        @endforeach

    </section>

    <section class="content-section">

        <div class="content-card">

            <h3>Description</h3>

            <p>
                {{ $data['membership']->plan_description }}
            </p>

        </div>

        <div class="content-card">

            <h3>Terms & Conditions</h3>

            <p>
                {!! nl2br(e($data['membership']->terms_and_conditions)) !!}
            </p>

        </div>

    </section>

</main>

@endsection
