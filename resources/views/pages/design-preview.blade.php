<!doctype html>
<html lang="en" data-site="{{ $siteKey }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $siteName }} — Meaningful matches, thoughtfully made</title>
  <meta name="description" content="Meet verified people who share your values, culture and hopes for the future.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,500;9..144,600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/design-preview.css') }}">
  <style>:root{--brand:{{ $sitePrimaryColor }};--brand-dark:{{ $siteSecondaryColor }};--accent:{{ $siteAccentColor }};--hero:url('{{ asset($siteHeroBackground) }}')}</style>
</head>
<body>
  <header class="topbar">
    <a class="brand" href="{{ route('design-preview') }}"><img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}"><span>{{ $siteName }}</span></a>
    <nav aria-label="Main navigation">
      <a href="#how">How it works</a><a href="#why">Why us</a><a href="{{ route('success-stories') }}">Stories</a><a href="{{ route('pricing') }}">Pricing</a>
    </nav>
    <div class="header-actions"><a class="text-link" href="{{ route('login-form') }}">Sign in</a><a class="button button-small" href="{{ route('login-form') }}#register">Create profile</a></div>
  </header>

  <main>
    <section class="hero">
      <div class="hero-shade"></div>
      <div class="hero-copy">
        <p class="eyebrow"><span></span>{{ $siteHeroBadge }}</p>
        <h1>A beautiful beginning,<br><em>rooted in who you are.</em></h1>
        <p class="lede">{{ $siteHeroSubtitle }}</p>
        <div class="hero-actions"><a class="button" href="{{ route('login-form') }}#register">Start your journey <span>→</span></a><a class="button-quiet" href="#how">See how it works</a></div>
        <div class="trust-line"><span class="avatars"><b>R</b><b>A</b><b>S</b></span><span><strong>{{ number_format($data['totalprofiles'] ?? 0) }}+</strong> genuine profiles and growing</span></div>
      </div>
      <aside class="match-card">
        <p class="card-label">A match worth meeting</p>
        <div class="portrait"><span class="verified">✓ Verified</span></div>
        <div class="match-info">
          <div><h2>{{ $data['femaleProfile']->full_name ?? 'Meet someone special' }}</h2><p>{{ isset($data['femaleProfile']) ? \Carbon\Carbon::parse($data['femaleProfile']->birth_date_time)->age . ' years · ' . $data['femaleProfile']->city_living_in : 'Profiles selected around your preferences' }}</p></div>
          <span class="heart">♡</span>
        </div>
      </aside>
      <div class="hero-note"><span>✦</span><p><strong>Compatibility, beyond checkboxes.</strong><br>Values, family and ambitions all matter.</p></div>
    </section>

    <section class="proof" aria-label="Trust highlights">
      <p>Made for serious relationships</p><div></div><p>Human-reviewed profiles</p><div></div><p>Privacy at every step</p><div></div><p>Family-friendly support</p>
    </section>

    <section class="intro" id="why">
      <div><p class="section-kicker">A more thoughtful way to meet</p><h2>Not more profiles.<br><em>More meaningful possibilities.</em></h2></div>
      <div><p>Finding a life partner deserves care, clarity and trust. {{ $siteName }} brings modern discovery together with the values that matter to you and your family.</p><a href="{{ route('about-us') }}">Our approach <span>→</span></a></div>
    </section>

    <section class="values">
      <article><span>01</span><div class="value-icon">◇</div><h3>Genuine by design</h3><p>Profile verification and thoughtful moderation help every introduction begin with confidence.</p></article>
      <article class="featured"><span>02</span><div class="value-icon">♡</div><h3>Matches with context</h3><p>Discover people through values, lifestyle, family background and real compatibility—not just filters.</p></article>
      <article><span>03</span><div class="value-icon">⌁</div><h3>Your privacy, respected</h3><p>You decide what to share, when to connect and who can access your contact information.</p></article>
    </section>

    <section class="steps" id="how">
      <div class="steps-copy"><p class="section-kicker light">Simple, warm, intentional</p><h2>From hello to<br><em>something lasting.</em></h2><p>We keep the experience simple so you can focus on what really matters: getting to know someone.</p><a class="button button-light" href="{{ route('login-form') }}#register">Create your free profile</a></div>
      <ol><li><span>1</span><div><h3>Tell us about yourself</h3><p>Create a detailed profile that reflects your story, values and aspirations.</p></div></li><li><span>2</span><div><h3>Discover compatible people</h3><p>Search with intention or explore recommendations chosen around your preferences.</p></div></li><li><span>3</span><div><h3>Connect with confidence</h3><p>Express interest, involve family when you wish, and take the conversation forward.</p></div></li></ol>
    </section>

    <section class="closing"><p class="section-kicker">Your story can begin here</p><h2>Some meetings change<br><em>everything.</em></h2><p>Join {{ number_format($data['totalprofiles'] ?? 0) }}+ people looking for a relationship built to last.</p><a class="button" href="{{ route('login-form') }}#register">Create your profile <span>→</span></a></section>
  </main>

  <footer><a class="brand" href="{{ route('design-preview') }}"><img src="{{ asset($siteLogo) }}" alt=""><span>{{ $siteName }}</span></a><p>{{ $siteFooterText }}</p><div><a href="{{ route('privacy-policy') }}">Privacy</a><a href="{{ route('terms-and-conditions') }}">Terms</a><a href="{{ route('contact-us') }}">Contact</a></div></footer>
</body>
</html>
