@extends('layouts.dashboard')

@section('title', 'Refer & Earn - ' . $siteName)

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/memberships.css') }}?v={{ filemtime(public_path('assets/css/memberships.css')) }}">
<style>
    .referral-page .referral-card { max-width: 680px; margin: 0 auto; padding: clamp(24px, 4vw, 40px); text-align: center; align-items: center; }
    .referral-page .referral-icon { width: 64px; height: 64px; border-radius: 18px; margin-bottom: 24px; }
    .referral-page .referral-icon svg { width: 30px; height: 30px; }
    .referral-page .referral-status { display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; margin-bottom: 16px; border: 1px solid var(--mp-border); border-radius: 30px; background: var(--mp-tint); color: var(--mp-accent); font-size: 11px; font-weight: 650; }
    .referral-page .referral-status svg { width: 13px; height: 13px; }
    .referral-page .referral-card h2 { margin: 0 0 12px; font-size: 24px; font-weight: 700; }
    .referral-page .referral-card p { max-width: 480px; margin: 0 0 28px; font-size: 14px; line-height: 1.8; color: var(--color-text-muted); }
    @media (max-width: 640px) { .referral-page .referral-card .mp-button { width: 100%; } }
</style>
@endsection

@section('content')
<section class="mp-page referral-page" aria-labelledby="referral-heading">
    <a href="{{ route('home') }}" class="mp-back"><i data-lucide="arrow-left" aria-hidden="true"></i> Back to dashboard</a>
    <header class="mp-heading">
        <span class="mp-eyebrow"><i data-lucide="gift" aria-hidden="true"></i> REFER &amp; EARN</span>
        <h1>A journey worth<br><span>sharing with friends.</span></h1>
        <p>We’re working on a new way to refer friends to {{ $siteName }}.</p>
    </header>
    <div class="mp-card referral-card">
        <span class="mp-small-icon referral-icon"><i data-lucide="users-round" aria-hidden="true"></i></span>
        <span class="referral-status"><i data-lucide="clock-3" aria-hidden="true"></i> Coming soon</span>
        <h2 id="referral-heading">Something to look forward to.</h2>
        <p>Refer &amp; Earn is on its way. We’re getting the details ready and will share more when it’s available. Thank you for being part of our community.</p>
        <a href="{{ route('home') }}" class="mp-button mp-button--primary"><i data-lucide="arrow-left" aria-hidden="true"></i> Back to dashboard</a>
    </div>
</section>
@endsection
