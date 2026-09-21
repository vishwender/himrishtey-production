@extends('layouts.public')

@section('title', 'Refund & Cancellation Policy - ' . $siteName)
@section('description', 'Review the refund and cancellation policy for ' . $siteName . ' membership purchases.')


@section('content')
<main class="legal-page" id="main-content">
  <section class="legal-hero" aria-labelledby="refundPolicyTitle">
    <p class="legal-kicker"><i data-lucide="scale" width="14" height="14"></i> Legal</p>
    <h1 id="refundPolicyTitle">Refund &amp; Cancellation Policy</h1>
    <p>Please review these terms carefully before purchasing a {{ $siteName }} membership plan.</p>
  </section>
  <article class="legal-card" aria-label="Refund and cancellation policy content">
    <header class="legal-card-header">
      <div class="legal-card-icon" aria-hidden="true">
        <i data-lucide="receipt-text" width="23" height="23"></i>
      </div>
      <h2>Membership payments and refunds</h2>
    </header>
    <div class="legal-content">
      @if (filled(strip_tags((string) $data))) {!! $data !!} @else <p class="legal-empty">The refund policy is being updated. Please contact support before making a purchase if you need clarification.</p> @endif
    </div>
  </article>
  <section class="legal-support"><i data-lucide="message-circle-question" width="22" height="22"></i>
    <p>Have a question about a payment, cancellation, or refund?</p><a class="public-cta public-cta-primary" href="{{ route('contact-us') }}">Contact Support</a>
  </section>
</main>
@endsection