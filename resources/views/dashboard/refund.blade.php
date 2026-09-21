@extends('layouts.dashboard')

@section('title', 'Refund & Cancellation Policy - Himrishtey')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/member-terms.css') }}">
@endsection

@section('content')

<main class="member-terms-page">

    <section class="member-terms-hero" aria-labelledby="memberRefundTitle">
        <p class="member-terms-kicker">Legal</p>
        <h1 id="memberRefundTitle">Refund &amp; Cancellation Policy</h1>
        <p class="member-terms-intro">Please review our payment, cancellation, and refund terms before purchasing a membership.</p>
    </section>

    <article class="member-terms-card" aria-label="Refund and cancellation policy content">
        <header class="member-terms-card-header">
            <div class="member-terms-icon" aria-hidden="true"><i data-lucide="receipt-text" width="23" height="23"></i></div>
            <h2>Membership payments and refunds</h2>
        </header>

        <section class="member-terms-content">
            {!! $page->refund_policy !!}
        </section>
    </article>

</main>

@endsection
