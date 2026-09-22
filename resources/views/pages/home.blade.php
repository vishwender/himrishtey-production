 <!doctype html>
 <html lang="en" data-site="{{ $siteKey }}">

 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width,initial-scale=1">
     <meta name="csrf-token" content="{{ csrf_token() }}">
     <script>
         try {
             const savedTheme = localStorage.getItem('site-theme') || localStorage.getItem('public-theme') || (matchMedia('(prefers-color-scheme:dark)').matches ? 'dark' : 'light');
             document.documentElement.dataset.theme = savedTheme;
         } catch (e) {}
     </script>
     @include('partials.google-analytics')
    @if (config('site.current.google_tag_manager_id'))
    <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer',{{ Illuminate\Support\Js::from(config('site.current.google_tag_manager_id')) }});</script>
<!-- End Google Tag Manager -->
    @endif
     <title>{{ $siteMetaTitle }}</title>
     <meta name="description" content="{{ $siteMetaDescription }}">
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
     @vite(['resources/css/home/home.css', 'resources/js/home.js'])
 </head>

 <body class="public-site tenant-{{ \Illuminate\Support\Str::slug($siteKey) }}" style="
        --site-primary: {{ $sitePrimaryColor }};
        --site-secondary: {{ $siteSecondaryColor }};
        --site-accent: {{ $siteAccentColor }};
        --brand: {{ $sitePrimaryColor }};
        --deep: {{ $siteSecondaryColor }};
        --gold: {{ $siteAccentColor }};
    ">
     @if (config('site.current.google_tag_manager_id'))
     <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ config('site.current.google_tag_manager_id') }}"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
     @endif
     @include('partials.public-header')
     <main>
         <section class="hero" style="--hero-image: url('{{ asset($siteHeroBackground) }}');">
             <img class="hero-mobile-image" src="{{ asset($siteHeroBackground) }}" alt="" fetchpriority="high">
             <div class="hero-copy">
                 <h1>Find someone<br><em>who feels like home.</em></h1>
                 <p>Meaningful connections. Genuine profiles.<br> A simple way to find your life partner.</p>
                 <div class="hero-buttons">
                     <a class="solid public-cta public-cta-primary" href="#featured">
                         <i data-lucide="heart" aria-hidden="true"></i>Find Matches
                     </a>
                     <a class="outline public-cta public-cta-secondary" href="{{ route('login-form') }}#register">
                         <i data-lucide="user-plus" aria-hidden="true"></i>Register Free
                     </a>
                 </div>
                 <div class="trusted">
                     <span class="mini-faces">
                         <i></i>
                         <i></i>
                         <i></i>
                     </span>
                     <small>Trusted by thousands of<br>happy members <i data-lucide="heart" aria-hidden="true"></i></small>
                 </div>
             </div>
         </section>

         <form class="finder" id="search" action="{{ auth('member')->check() ? route('search-results') : route('login-form') . '#register' }}" method="get">
             @auth('member')
             <input type="hidden" name="_source" value="advanced">
             @endauth
             <label for="lookingFor">
                 <span>Looking for</span>
                 <span class="finder-control">
                     <i data-lucide="user-search" aria-hidden="true"></i>
                     <select id="lookingFor" name="looking_for">
                         <option value="Female">Bride</option>
                         <option value="Male">Groom</option>
                     </select>
                 </span>
             </label>
             <label for="ageFrom">
                 <span>Age</span>
                 <span class="finder-control">
                     <i data-lucide="calendar-range" aria-hidden="true"></i>
                     <span class="finder-age-fields">
                         <select id="ageFrom" name="partner_age_from" aria-label="Minimum age">
                             @for ($age = 18; $age <= 70; $age++)
                                 <option value="{{ $age }}" @selected($age===24)>{{ $age }}</option>
                                 @endfor
                         </select>
                         <span aria-hidden="true">to</span>
                         <select id="ageTo" name="partner_age_to" aria-label="Maximum age">
                             @for ($age = 18; $age <= 70; $age++)
                                 <option value="{{ $age }}" @selected($age===30)>{{ $age }}</option>
                                 @endfor
                         </select>
                     </span>
                 </span>
             </label>
             <label for="partnerReligion">
                 <span>Religion</span>
                 <span class="finder-control">
                     <i data-lucide="landmark" aria-hidden="true"></i>
                     <select id="partnerReligion" name="partner_religion">
                         <option value="">Any</option>
                         <option value="Hindu">Hindu</option>
                         <option value="Sikh">Sikh</option>
                         <option value="Christian">Christian</option>
                         <option value="Buddhist">Buddhist</option>
                         <option value="Muslim">Muslim</option>
                     </select>
                 </span>
             </label>
             <label for="cityName">
                 <span>City</span>
                 <span class="finder-control">
                     <i data-lucide="map-pin" aria-hidden="true"></i>
                     <select id="cityName" name="city_name">
                         <option value="">Any city</option>
                         @foreach($searchCities as $city)
                         <option value="{{ $city }}">{{ $city }}</option>
                         @endforeach
                     </select>
                 </span>
             </label>
             <button class="public-cta public-cta-primary" type="submit">
                 <i data-lucide="search" aria-hidden="true"></i>
                 <span>Search Matches</span>
             </button>
         </form>

         <section class="trust-strip">
             <article><i data-lucide="badge-check" aria-hidden="true"></i>
                 <div><b>100% Verified Profiles</b><small>Manually verified for your safety</small></div>
             </article>
             <article><i data-lucide="shield-check" aria-hidden="true"></i>
                 <div><b>Secure &amp; Private</b><small>Your privacy is our priority</small></div>
             </article>
             <article><i data-lucide="users" aria-hidden="true"></i>
                 <div><b>Thousands of Matches</b><small>New matches every day</small></div>
             </article>
             <article><i data-lucide="headset" aria-hidden="true"></i>
                 <div><b>24/7 Customer Support</b><small>We are here to help you</small></div>
             </article>
         </section>

         @if($featuredProfiles->isNotEmpty())
         <section class="matches wrap" id="featured" data-profile-slider aria-label="Verified members" aria-roledescription="carousel">
             <h2>Meet our verified members</h2>
             <div class="ornament"><i data-lucide="heart" aria-hidden="true"></i></div>
             <div class="profile-slider-controls" hidden>
                 <button type="button" data-slider-prev aria-label="Previous profiles" aria-controls="verified-profile-track">←</button>
                 <button type="button" data-slider-next aria-label="Next profiles" aria-controls="verified-profile-track">→</button>
             </div>
             <div class="cards profile-slider-track" id="verified-profile-track" tabindex="0" aria-label="Verified profiles; use arrow keys to browse">
                 @foreach($featuredProfiles as $profile)
                 @php
                 $photoUrl = \App\Website\Services\ProfilePhotoUrl::get($profile->photo);
                 @endphp
                 <article class="profile-card">
                     <div class="profile-photo">
                         <img src="{{ $photoUrl }}" alt="{{ $profile->full_name }}" loading="lazy">
                         <span>● Verified</span>
                     </div>
                     <div>
                         <b>{{ $profile->full_name }}@if($profile->age), {{ $profile->age }}@endif</b>
                         <small>{{ $profile->city_living_in }}<br>{{ $profile->occupation }}</small>
                     </div>
                 </article>
                 @endforeach
             </div><a class="more outline public-cta public-cta-secondary" href="{{ route('login-form') }}#register">View More Profiles</a>
         </section>
         @endif

         <section class="split wrap">
             <article class="how" id="how">
                 <h2>How it works</h2>
                 <div class="ornament"><i data-lucide="heart" aria-hidden="true"></i></div>
                 <div class="three">
                     <div>
                         <i data-lucide="user-round-plus" aria-hidden="true"></i>
                         <b>1. Create Your Profile</b>
                         <small>Sign up and create your profile in just a few minutes.</small>
                     </div>
                     <div>
                         <i data-lucide="search" aria-hidden="true"></i>
                         <b>2. Discover Matches</b>
                         <small>Get matched with compatible profiles tailored for you.</small>
                     </div>
                     <div>
                         <i data-lucide="messages-square" aria-hidden="true"></i>
                         <b>3. Start a Conversation</b>
                         <small>Connect, chat and take the first step towards a beautiful journey.</small>
                     </div>
                 </div>
             </article>
             <article class="why">
                 <h2>Why choose {{ $siteName }}?</h2>
                 <div class="ornament"><i data-lucide="heart" aria-hidden="true"></i></div>
                 <div class="why-grid">
                     <p><i data-lucide="badge-check" aria-hidden="true"></i><span><b>Verified &amp; Genuine</b><small>Every profile is manually verified</small></span></p>
                     <p><i data-lucide="eye-off" aria-hidden="true"></i><span><b>Privacy Controls</b><small>You are in control of your privacy</small></span></p>
                     <p><i data-lucide="sparkles" aria-hidden="true"></i><span><b>Smart Matching</b><small>Advanced matching for better connections</small></span></p>
                     <p><i data-lucide="users-round" aria-hidden="true"></i><span><b>Community Focused</b><small>Find matches from your community</small></span></p>
                     <p><i data-lucide="shield-check" aria-hidden="true"></i><span><b>Secure Contact Access</b><small>Connect only when comfortable</small></span></p>
                     <p><i data-lucide="headset" aria-hidden="true"></i><span><b>Dedicated Support</b><small>Friendly support whenever needed</small></span></p>
                 </div>
             </article>
         </section>

         <section class="stories wrap" id="stories">
             <h2>Real stories. Real happiness.</h2>
             <div class="ornament"><i data-lucide="heart" aria-hidden="true"></i></div>
             <div class="story-grid">
                 <article><img src="{{ asset('uploads/success-stories/default-story.png') }}" alt="Happy couple">
                     <div><b>Pooja &amp; Ankush</b><strong>Shimla, Himachal Pradesh</strong>
                         <p>“We met on {{ $siteName }} and instantly connected. Today, we are happily building our future together.”</p>
                         <small><i data-lucide="heart" aria-hidden="true"></i> Married on 12th Feb 2024</small>
                     </div>
                 </article>
                 <article><img src="{{ asset('uploads/success-stories/default-story.png') }}" alt="Happy couple">
                     <div><b>Megha &amp; Saurav</b><strong>Kangra, Himachal Pradesh</strong>
                         <p>“Thanks to {{ $siteName }}, we found not just a life partner but a best friend for life.”</p>
                         <small><i data-lucide="heart" aria-hidden="true"></i> Married on 5th Nov 2023</small>
                     </div>
                 </article>
             </div><a class="more outline public-cta public-cta-secondary" href="{{ route('success-stories') }}">Read More Success Stories</a>
         </section>

         @php
         $instagram = config('site.current.instagram', []);

         $enabled = (bool) ($instagram['enabled'] ?? false);
         $username = $instagram['username'] ?? null;
         $instagramUrl = $instagram['url'] ?? '#';

         @endphp

         @if($enabled && $instagramItems->isNotEmpty())

         <section class="communities wrap">
             <h2>Latest from Instagram</h2>

             <div class="community-grid">

                 @foreach($instagramItems as $item)

                 @php
                 $mediaType = $item['media_type'] ?? '';
                 $productType = $item['media_product_type'] ?? '';

                 $isReel =
                 $mediaType === 'VIDEO'
                 && $productType === 'REELS';

                 $image =
                 $item['thumbnail_url']
                 ?? $item['media_url']
                 ?? null;

                 $permalink =
                 $item['permalink']
                 ?? $instagramUrl;

                 $caption =
                 $item['caption']
                 ?? 'View on Instagram';
                 @endphp

                 @if($image)

                 <a
                     href="{{ $permalink }}"
                     target="_blank"
                     rel="noopener noreferrer"
                     class="instagram-community-card"
                     style="--bg:url('{{ $image }}')">

                 </a>
                 @endif
                 @endforeach
             </div>
         </section>
         @endif


         {{--<section class="app-cta">
             <div class="phone"><i data-lucide="smartphone" aria-hidden="true"></i><small>{{ $siteName }}</small></div>
         <div>
             <h2>Take the next step towards<br>your happily ever after.</h2>
             <p>Create your free profile today and start your journey.</p>
         </div>
         @if($siteAndroidAppUrl)
         <a class="store" href="{{ $siteAndroidAppUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Download {{ $siteName }} from Google Play">
             <svg class="store-icon play-icon" viewBox="0 0 28 31" aria-hidden="true">
                 <path fill="#00d4ff" d="M2 2.2 16.3 15.5 2 28.8c-.6-.7-1-1.7-1-2.9V5.1c0-1.2.4-2.2 1-2.9Z" />
                 <path fill="#00e676" d="m2.8 1.5 17.5 10-4 4L2 2.2c.2-.3.5-.5.8-.7Z" />
                 <path fill="#ffcf3f" d="m16.3 15.5 4-4 5.1 2.9c1.5.8 1.5 2.3 0 3.2l-5.1 2.9-4-5Z" />
                 <path fill="#ff4b55" d="m2 28.8 14.3-13.3 4 5L2.8 29.5c-.3-.2-.6-.4-.8-.7Z" />
             </svg>
             <span>Get it on<b>Google Play</b></span>
         </a>
         @endif @if($siteIosAppUrl)
         <a class="store" href="{{ $siteIosAppUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Download {{ $siteName }} from the Apple App Store">
             <svg class="store-icon apple-icon" viewBox="0 0 24 24" aria-hidden="true">
                 <path fill="currentColor" d="M17.1 12.5c0-2.6 2.1-3.9 2.2-4-1.2-1.8-3.1-2-3.8-2-1.6-.2-3.1.9-3.9.9-.8 0-2-1-3.4-.9-1.7 0-3.4 1-4.3 2.6-1.9 3.2-.5 8 1.3 10.6.9 1.3 1.9 2.7 3.3 2.6 1.3-.1 1.8-.8 3.4-.8 1.6 0 2 .8 3.4.8 1.4 0 2.3-1.3 3.2-2.6 1-1.5 1.5-3 1.5-3.1-.1 0-2.9-1.1-2.9-4.1ZM14.4 4.8c.7-.9 1.2-2.1 1.1-3.3-1.1.1-2.4.7-3.2 1.6-.7.8-1.3 2-1.1 3.2 1.2.1 2.4-.6 3.2-1.5Z" />
             </svg>
             <span>Download on the<b>App Store</b></span>
         </a>
         @endif
         <a class="cta-white public-cta public-cta-primary" href="{{ route('login-form') }}#register">Create Free Profile
             <i class="cta-profile-icon" data-lucide="user-plus" aria-hidden="true"></i>
         </a>
         </section>--}}
         <section class="app-download-section reveal">

             {{-- LEFT CONTENT --}}
             <div class="app-download-content">

                 @if ($siteAndroidAppQr || $siteIosAppQr)
                 <div class="app-qr-codes">
                     @if ($siteAndroidAppQr)
                     <figure class="app-qr">
                         <img
                             src="{{ asset($siteAndroidAppQr) }}"
                             alt="Scan to download {{ $siteName }} on Google Play">
                         <figcaption>Android</figcaption>
                     </figure>
                     @endif
                     @if ($siteKey === 'himrishtey.com' && $siteIosAppQr)
                     <figure class="app-qr">
                         <img
                             src="{{ asset($siteIosAppQr) }}"
                             alt="Scan to download {{ $siteName }} on the App Store">
                         <figcaption>iOS</figcaption>
                     </figure>
                     @endif
                 </div>
                 @endif

                 <h2>
                     To speed up your partner search,<br>
                     download <strong>{{ $siteName }} App</strong>
                 </h2>

                 <div class="app-store-buttons">

                     {{-- GOOGLE PLAY --}}
                     <a
                         href="{{ $siteAndroidAppUrl }}"
                         target="_blank"
                         rel="noopener noreferrer"
                         class="app-store-btn">
                         <svg
                             class="app-store-icon play-icon"
                             viewBox="0 0 28 31"
                             aria-hidden="true">
                             <path fill="#00d4ff"
                                 d="M2 2.2 16.3 15.5 2 28.8c-.6-.7-1-1.7-1-2.9V5.1c0-1.2.4-2.2 1-2.9Z" />
                             <path fill="#00e676"
                                 d="m2.8 1.5 17.5 10-4 4L2 2.2c.2-.3.5-.5.8-.7Z" />
                             <path fill="#ffcf3f"
                                 d="m16.3 15.5 4-4 5.1 2.9c1.5.8 1.5 2.3 0 3.2l-5.1 2.9-4-5Z" />
                             <path fill="#ff4b55"
                                 d="m2 28.8 14.3-13.3 4 5L2.8 29.5c-.3-.2-.6-.4-.8-.7Z" />
                         </svg>

                         <span>
                             <small>GET IT ON</small>
                             <strong>Google Play</strong>
                         </span>
                     </a>
                     {{-- APP STORE --}}
                     <a
                         href="https://apps.apple.com/in/app/him-rishtey/id1669261836"
                         target="_blank"
                         rel="noopener noreferrer"
                         class="app-store-btn">
                         <svg
                             class="app-store-icon apple-icon"
                             viewBox="0 0 24 24"
                             aria-hidden="true">
                             <path
                                 fill="currentColor"
                                 d="M17.1 12.5c0-2.6 2.1-3.9 2.2-4-1.2-1.8-3.1-2-3.8-2-1.6-.2-3.1.9-3.9.9-.8 0-2-1-3.4-.9-1.7 0-3.4 1-4.3 2.6-1.9 3.2-.5 8 1.3 10.6.9 1.3 1.9 2.7 3.3 2.6 1.3-.1 1.8-.8 3.4-.8 1.6 0 2 .8 3.4.8 1.4 0 2.3-1.3 3.2-2.6 1-1.5 1.5-3 1.5-3.1-.1 0-2.9-1.1-2.9-4.1ZM14.4 4.8c.7-.9 1.2-2.1 1.1-3.3-1.1.1-2.4.7-3.2 1.6-.7.8-1.3 2-1.1 3.2 1.2.1 2.4-.6 3.2-1.5Z" />
                         </svg>

                         <span>
                             <small>Download on the</small>
                             <strong>App Store</strong>
                         </span>
                     </a>


                 </div>

                 <div class="app-download-meta">
                     <p>
                         {{ $siteName }} – Trusted Matrimony App
                     </p>

                     <p class="download-count">
                         Find your perfect match anywhere, anytime.
                     </p>

                     <div class="app-rating">
                         <span class="rating-number">4.2</span>

                         <div>
                             <div class="stars">
                                 ★ ★ ★ ★ <span>★</span>
                             </div>

                             <small>Based on Customer Reviews</small>
                         </div>
                     </div>
                 </div>

             </div>


             {{-- RIGHT MOCKUPS --}}
             <div class="app-phone-showcase">

                 <div class="app-decoration app-decoration-one"></div>
                 <div class="app-decoration app-decoration-two"></div>

                 <div class="phone-mockup phone-mockup-main">
                     <img
                         src="{{ asset('assets/images/app/profile-screen.png') }}"
                         alt="{{ $siteName }} profile screen">
                 </div>

                 <div class="phone-mockup phone-mockup-secondary">
                     <img
                         src="{{ asset('assets/images/app/matches-screen.png') }}"
                         alt="{{ $siteName }} matches screen">
                 </div>

             </div>

         </section>
     </main>
     @include('partials.public-footer')
 </body>

 </html>
