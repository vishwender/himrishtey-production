@extends('layouts.public')
@section('title', 'Terms & Conditions - ' . $siteName)
@section('description', 'Read the terms governing profiles, memberships, payments, and use of ' . $siteName . '.')

@section('content')
<main class="legal-page" id="main-content">
  <section class="legal-hero" aria-labelledby="termsTitle">
    <p class="legal-kicker"><i data-lucide="scale" width="14" height="14"></i> Legal</p>
    <h1 id="termsTitle">Terms &amp; Conditions</h1>
    <p>Please read these terms carefully before using {{ $siteName }} or purchasing a membership.</p>
  </section>
  <article class="legal-card" aria-label="Terms and conditions content">
    <header class="legal-card-header">
      <div class="legal-card-icon" aria-hidden="true"><i data-lucide="scroll-text" width="23" height="23"></i></div>
      <h2>Platform and membership terms</h2>
    </header>
    <div class="legal-content">
      @if (filled(strip_tags((string) $data))) {!! $data !!} @else <p class="legal-empty">The terms and conditions are being updated. Please contact support if you need assistance.</p> @endif
    </div>
  </article>
  <section class="legal-support"><i data-lucide="message-circle-question" width="22" height="22"></i>
    <p>Need help understanding a membership or account term?</p><a class="public-cta public-cta-primary" href="{{ route('contact-us') }}">Contact Support</a>
  </section>
</main>
@endsection