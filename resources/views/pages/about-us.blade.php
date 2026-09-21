@extends('layouts.public')
@section('title', 'About ' . $siteName)
@section('description', 'Learn how ' . $siteName . ' helps families discover genuine, meaningful matrimonial connections.')

@section('styles')

@endsection

@section('content')
<div class="editorial-page about-v2">
  <section class="editorial-hero about-v2-hero">
    <div class="editorial-hero-copy"><span class="editorial-kicker">About {{ $siteName }}</span>
      <h1>Relationships begin with trust.<br><em>We help them grow with care.</em></h1>
      <p>{{ $siteName }} brings families and individuals together through genuine profiles, thoughtful discovery and a respectful approach to matrimony.</p>
    </div>
    <div class="about-hero-art"><span class="art-ring ring-one"></span><span class="art-ring ring-two"></span>
      <div class="art-heart">♡</div>
      <p><b>Built around people,</b><br>not just profiles.</p>
    </div>
  </section>

  <section class="about-intro-v2 editorial-wrap">
    <div><span class="editorial-kicker">Why we exist</span>
      <h2>A modern platform,<br><em>grounded in family values.</em></h2>
    </div>
    <div class="about-cms">{!! $aboutUs ?: '<p>Finding a life partner is one of life’s most meaningful decisions. We created a place where people can search with confidence, connect at their own pace and include their families whenever they choose.</p>' !!}</div>
  </section>

  <section class="about-values editorial-wrap">
    <article><span>01</span><i data-lucide="badge-check"></i>
      <h3>Genuine connections</h3>
      <p>We encourage complete profiles and thoughtful verification so every conversation can begin with greater confidence.</p>
    </article>
    <article><span>02</span><i data-lucide="shield-check"></i>
      <h3>Privacy with control</h3>
      <p>Your information remains yours. You choose when to connect and when contact details can be shared.</p>
    </article>
    <article><span>03</span><i data-lucide="heart-handshake"></i>
      <h3>Respect at every step</h3>
      <p>Our experience is designed for serious intentions, family participation and culturally meaningful introductions.</p>
    </article>
  </section>

  <section class="about-story">
    <div class="about-story-visual"><img src="{{ asset('assets/images/landing-hero-v2.png') }}" alt="A couple beginning their journey together"></div>
    <div class="about-story-copy"><span class="editorial-kicker">Our promise</span>
      <h2>More than a match.<br><em>A meaningful beginning.</em></h2>
      <p>Technology can help people discover one another, but trust and compatibility build relationships. We balance useful search tools with human values, safety and space for every story to unfold naturally.</p>
      <div class="about-promise-points" aria-label="Our commitments"><span><i data-lucide="shield-check" width="17" height="17"></i> Safety first</span><span><i data-lucide="heart-handshake" width="17" height="17"></i> People focused</span></div><a class="editorial-button public-cta public-cta-primary" href="{{ route('login-form') }}#register">Start your journey <span>→</span></a>
    </div>
  </section>

  <section class="about-steps editorial-wrap"><span class="editorial-kicker">What guides us</span>
    <h2>Simple principles. Lasting impact.</h2>
    <div>
      <p><b>Be genuine</b><small>Clear information and honest intentions make better introductions.</small></p>
      <p><b>Be respectful</b><small>Every person and every family deserves dignity throughout the journey.</small></p>
      <p><b>Keep improving</b><small>We listen, learn and make the matching experience safer and simpler.</small></p>
      <p><b>Stay human</b><small>Behind every profile is a person hoping to find something meaningful.</small></p>
    </div>
  </section>

  <section class="editorial-cta"><span class="editorial-kicker">Your next chapter</span>
    <h2>Ready to meet someone<br><em>who feels like home?</em></h2><a class="editorial-button public-cta public-cta-primary" href="{{ route('login-form') }}#register">Create your free profile <span>→</span></a>
  </section>
</div>
@endsection