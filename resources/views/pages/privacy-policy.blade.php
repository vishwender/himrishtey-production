@extends('layouts.public')
@section('title', 'Privacy Policy - ' . $siteName)
@section('description', 'Learn how ' . $siteName . ' collects, uses, protects, and shares your personal information.')

@section('content')
<main class="legal-page" id="main-content">
  <section class="legal-hero" aria-labelledby="ppTitle">
    <p class="legal-kicker"><i data-lucide="scale" width="14" height="14"></i> Legal</p>
    <h1 id="ppTitle">Privacy Policy</h1>
    <p>Your privacy matters to us. Learn how {{ $siteName }} collects, uses, protects, and shares your information.</p>
  </section>
  <article class="legal-card" aria-label="Privacy policy content">
    <header class="legal-card-header">
      <div class="legal-card-icon" aria-hidden="true"><i data-lucide="shield-check" width="23" height="23"></i></div>
      <h2>How we handle your information</h2>
    </header>
    <div class="legal-content">
      @if (filled(strip_tags((string) $data))) {!! $data !!} @else <p class="legal-empty">The privacy policy is being updated. Please contact support if you have a question about your data.</p> @endif
    </div>
  </article>
  <section class="legal-support"><i data-lucide="message-circle-question" width="22" height="22"></i>
    <p>Have a question about privacy or your personal data?</p><a class="public-cta public-cta-primary" href="{{ route('contact-us') }}">Contact Support</a>
  </section>
</main>
@endsection