@extends('layouts.dashboard')

@section('title', 'Terms & Conditions - Himrishtey')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/member-terms.css') }}">
@endsection

@section('content')

<main class="member-terms-page">

    <section class="member-terms-hero" aria-labelledby="memberTermsTitle">
        <p class="member-terms-kicker">Legal</p>
        <h1 id="memberTermsTitle">Terms &amp; Conditions</h1>
        <p class="member-terms-intro">Please read these terms carefully before using HimRishtey or managing your membership.</p>
    </section>

    <article class="member-terms-card" aria-label="Terms and conditions content">
        <header class="member-terms-card-header">
            <div class="member-terms-icon" aria-hidden="true"><i data-lucide="scroll-text" width="23" height="23"></i></div>
            <h2>Membership terms</h2>
        </header>

        <section class="member-terms-content">

            {!! $page->terms_and_conditions !!}

        </section>

    </article>

</main>

@endsection
