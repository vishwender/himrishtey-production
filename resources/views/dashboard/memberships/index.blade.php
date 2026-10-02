@extends('layouts.dashboard')

@section('title', 'Memberships - ' . $siteName)

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/memberships.css') }}?v={{ filemtime(public_path('assets/css/memberships.css')) }}">
@endsection

@section('content')
<section class="mp-page" aria-labelledby="membership-heading">
    <header class="mp-heading">
        <span class="mp-eyebrow"><i data-lucide="heart-handshake" aria-hidden="true"></i> MADE FOR YOUR JOURNEY</span>
        <h1 id="membership-heading">Your person is worth<br><span>taking the next step.</span></h1>
        <p>Explore our memberships and find the right way to connect.</p>
    </header>
    <div class="mp-section-heading"><div><span class="mp-overline">START HERE</span><h2>Choose your membership</h2></div><span class="mp-detail">Compare plans, benefits &amp; validity</span></div>
    <div class="mp-grid mp-grid--memberships">
        @forelse($data['memberships'] as $membership)
        <a href="{{ route('plans', $membership->id) }}" class="mp-card mp-category">
            <div class="mp-card-top">@include('dashboard.memberships.plan-icon', ['planName' => $membership->plan_name, 'icon' => $loop->index % 2 ? 'sparkles' : 'heart'])<span class="mp-category-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span></div>
            <h3>{{ $membership->plan_name }}</h3>
            <p>{{ $membership->plan_description }}</p>
            <span class="mp-category-link">Explore plans <i data-lucide="arrow-up-right" aria-hidden="true"></i></span>
        </a>
        @empty
        <div class="mp-empty"><h3>Memberships will be available soon</h3><p>Request a callback below and we’ll help you with the next step.</p></div>
        @endforelse
    </div>
    <section class="mp-help" aria-labelledby="membership-help">
        <div class="mp-help-copy"><span class="mp-small-icon"><i data-lucide="headset" aria-hidden="true"></i></span><div><span class="mp-overline">LET’S TALK IT THROUGH</span><h2 id="membership-help">A little guidance goes a long way.</h2><p>Our team can help you compare memberships and answer your questions.</p></div></div>
        <div class="mp-help-action"><button type="button" id="callbackBtn" class="mp-button mp-button--primary" data-url="{{ route('callback.request') }}" data-status-url="{{ route('callback.status') }}">Request a Callback</button><p class="mp-cooldown"><i data-lucide="clock-3" aria-hidden="true"></i> Request cooldown <span id="timer" role="timer" aria-label="Time until another callback request">00:00</span></p></div>
    </section>
</section>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/memberships.js') }}?v={{ filemtime(public_path('assets/js/memberships.js')) }}"></script>
@endsection
