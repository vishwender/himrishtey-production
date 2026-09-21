@extends('layouts.dashboard')

@section('title', 'Privacy Policy - Himrishtey')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/member-terms.css') }}">
@endsection

@section('content')

<main class="member-terms-page">

    <section class="member-terms-hero" aria-labelledby="memberPrivacyTitle">
        <p class="member-terms-kicker">Legal</p>
        <h1 id="memberPrivacyTitle">Privacy Policy</h1>
        <p class="member-terms-intro">Learn how HimRishtey collects, uses, and protects your information.</p>
    </section>

    <article class="member-terms-card" aria-label="Privacy policy content">
        <header class="member-terms-card-header">
            <div class="member-terms-icon" aria-hidden="true"><i data-lucide="shield-check" width="23" height="23"></i></div>
            <h2>How we handle your information</h2>
        </header>

        <section class="member-terms-content">

            {!! $page->privacy_policy !!}

        </section>

    </article>

</main>

@endsection
