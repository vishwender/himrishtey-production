<!DOCTYPE html>
<html lang="en" data-theme="light" data-site="{{ $siteKey }}">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script>
    try {
      const savedTheme = localStorage.getItem('site-theme') || localStorage.getItem('hr-theme') || localStorage.getItem('public-theme') || 'light';
      document.documentElement.dataset.theme = savedTheme;
    } catch (e) {}
  </script>
  <title>@yield('title', $siteName . ' – Find Your Life Partner')</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v=20260827-logo2" />
  <link rel="stylesheet" href="{{ asset('assets/css/rateus.css') }}?v={{ filemtime(public_path('assets/css/rateus.css')) }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/toast-manager.css') }}" />
  @yield('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/theme-overrides.css') }}?v={{ filemtime(public_path('assets/css/theme-overrides.css')) }}" />
  <style>
    :root {
      --site-primary: {
          {
          $sitePrimaryColor ?? '#b92c3d'
        }
      }

      ;

      --site-secondary: {
          {
          $siteSecondaryColor ?? '#2f2d5c'
        }
      }

      ;

      --site-accent: {
          {
          $siteAccentColor ?? '#f4c86c'
        }
      }

      ;
      --brand: var(--site-primary);
      --deep: var(--site-secondary);
      --gold: var(--site-accent);
    }
  </style>
  @include('partials.google-analytics')
</head>

<body class="site-{{ \Illuminate\Support\Str::slug($siteKey) }}">
  <!-- Skip link -->
  <!-- <a href="#main-content" class="skip-link">Skip to main content</a> -->

  <!-- SIDEBAR OVERLAY -->
  <div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>
  <!-- SIDEBAR / SIDE DRAWER -->
  <aside class="sidebar" id="sidebar" role="complementary" aria-label="Navigation menu">
    <div class="sidebar-header">
      <div class="sidebar-profile-card" onclick="window.location='#'">
        <div class="sidebar-avatar-wrap">
          <img
            src="{{ $dashboardMember?->photo ? \App\Website\Services\ProfilePhotoUrl::get($dashboardMember->photo) : asset('images/profile_photos/boy.jpg') }}"
            alt="{{ $dashboardMember?->full_name ?? 'User' }}"
            width="80"
            height="80"
            loading="lazy"
            class="sidebar-avatar"
            id="profileAvatar" />
          <span class="sidebar-avatar-badge" aria-hidden="true">
            <i data-lucide="camera" width="12" height="12"></i>
          </span>
          <input
            type="file"
            id="profilePhotoInput"
            accept="image/*"
            hidden>
        </div>
        <div class="sidebar-user-info">
          <h2 class="sidebar-user-name">{{ $dashboardMember?->full_name ?? 'User' }}</h2>
          <span class="sidebar-label">Profile ID</span>
          <span class="sidebar-value">{{ $dashboardMember?->profile_id ?? 'N/A' }}</span>
          <span class="sidebar-label">Membership</span>
          <span class="sidebar-status active">Active</span>
          <span class="sidebar-label">Plan</span>
          <span class="sidebar-plan-name">{{ $dashboardPlan?->plan_name ?? 'Free' }}</span>
        </div>
      </div>
      <button class="sidebar-close-btn" id="sidebarClose" aria-label="Close menu">
        <i data-lucide="x" width="20" height="20"></i>
      </button>
    </div>

    <nav class="sidebar-nav" aria-label="Main navigation">
      <span class="sidebar-nav-section-label">Menu</span>
      <ul role="list">
        <li><a href="{{ route('home') }}" class="sidebar-nav-item"><i data-lucide="home" width="18" height="18"></i><span>Home</span></a></li>
        <li><a href="{{ route('memberships') }}" class="sidebar-nav-item"><i data-lucide="shield" width="18" height="18"></i><span>Membership</span></a></li>
        <li><a href="{{ route('edit-profile') }}" class="sidebar-nav-item"><i data-lucide="edit-3" width="18" height="18"></i><span>Edit Profile</span></a></li>
        <li><a href="{{ route('quick-search') }}" class="sidebar-nav-item"><i data-lucide="search" width="18" height="18"></i><span>Quick Search</span></a></li>
        <li><a href="{{ route('advance-search') }}" class="sidebar-nav-item"><i data-lucide="sliders" width="18" height="18"></i><span>Advanced Search</span></a></li>
        <li><a href="{{ route('search-by-profile-id') }}" class="sidebar-nav-item"><i data-lucide="user-search" width="18" height="18"></i><span>Search by Profile ID</span></a></li>
        <li><a href="{{ route('interest-box') }}" class="sidebar-nav-item"><i data-lucide="inbox" width="18" height="18"></i><span>Interest Box</span></a></li>
        <li><a href="{{ route('view-my-profile') }}" class="sidebar-nav-item"><i data-lucide="eye" width="18" height="18"></i><span>View My Profile</span></a></li>
        <li><a href="{{ route('change-password') }}" class="sidebar-nav-item"><i data-lucide="key-round" width="18" height="18"></i><span>Change Password</span></a></li>
        <li><a href="{{ route('viewed-contacts') }}" class="sidebar-nav-item"><i data-lucide="phone" width="18" height="18"></i><span>Viewed Contact</span></a></li>
        <li><a href="{{ route('referral') }}" class="sidebar-nav-item"><i data-lucide="gift" width="18" height="18"></i><span>Refer &amp; Earn</span></a></li>
        <li><a href="{{ route('member.success-stories') }}" class="sidebar-nav-item"><i data-lucide="trophy" width="18" height="18"></i><span>Success Stories</span></a></li>
        <li><a href="{{ route('delete-profile') }}" class="sidebar-nav-item"><i data-lucide="user-x" width="18" height="18"></i><span>Delete Profile</span></a></li>
        <li><a href="{{route('member.refund-policy')}}" class="sidebar-nav-item"><i data-lucide="file-text" width="18" height="18"></i><span>Refund &amp; Cancellation</span></a></li>
        <li><a href="{{ route('member.privacy-policy') }}" class="sidebar-nav-item"><i data-lucide="lock" width="18" height="18"></i><span>Privacy Policy</span></a></li>
        <li><a href="javascript:void(0)" class="sidebar-nav-item" id="openRateModal"><i data-lucide="star" width="18" height="18"></i><span>Rate Us</span></a></li>
        <li><a href="tel:9857102002" class="sidebar-nav-item"><i data-lucide="phone-call" width="18" height="18"></i><span>Helpline: 9857102002</span></a></li>
        <li><a href="{{route('member.terms-and-conditions')}}" class="sidebar-nav-item"><i data-lucide="scroll-text" width="18" height="18"></i><span>Terms &amp; Conditions</span></a></li>
        <li>
          <form method="POST" action="{{ route('member-logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="sidebar-nav-item sidebar-logout-btn">
              <i data-lucide="log-out" width="18" height="18"></i><span>Logout</span>
            </button>
          </form>
        </li>
      </ul>
    </nav>
  </aside>
  <!-- TOP NAVBAR -->
  <header class="top-navbar" role="banner">
    <div class="navbar-inner container-fluid">
      <div class="navbar-left">
        <button class="hamburger-btn" id="sidebarToggle" aria-label="Open menu" aria-expanded="false" aria-controls="sidebar">
          <i data-lucide="menu" width="22" height="22"></i>
        </button>
        <div class="navbar-brand">
          <a href="{{route('home')}}"><img src="{{ asset($siteLogo) }}" alt="{{ $siteName }} Logo" class="navbar-logo"></a>
        </div>
        <span class="navbar-greeting">Hi, <strong>{{ $dashboardMember?->full_name ?? 'User' }}</strong> 👋</span>
      </div>
      <div class="navbar-right">
        <div class="navbar-wallet">
          <i data-lucide="wallet" width="18" height="18"></i>
          <span class="wallet-balance">₹{{ $dashboardMember?->wallet_balance ?? '0' }}</span>
        </div>
        <button class="theme-toggle-btn" data-theme-toggle aria-label="Switch to dark mode">
          <i data-lucide="moon" width="18" height="18"></i>
        </button>
        <button class="profile-avatar-btn" id="profileQuickViewBtn" aria-label="Open profile quick view" aria-expanded="false" aria-controls="profileQuickView">
          <img src="{{ $dashboardMember?->photo ? \App\Website\Services\ProfilePhotoUrl::get($dashboardMember->photo) : asset('images/profile_photos/boy.jpg') }}" alt="{{ $dashboardMember?->full_name ?? 'User' }}" width="40" height="40" class="navbar-avatar" loading="lazy" id="profileAvatar" />
          <span class="online-dot" aria-hidden="true"></span>
        </button>
      </div>
    </div>
  </header>

  <!-- PROFILE QUICK VIEW POPUP -->
  <div class="pqv-backdrop" id="pqvBackdrop" aria-hidden="true"></div>
  <div class="profile-quickview" id="profileQuickView" role="dialog" aria-modal="true" aria-label="Profile Quick View" aria-hidden="true">
    <div class="pqv-inner">
      <button class="pqv-close" id="pqvClose" aria-label="Close">
        <i data-lucide="x" width="18" height="18"></i>
      </button>

      <div class="pqv-user-row">
        <div class="pqv-avatar-wrap">
          <img src="{{ $dashboardMember?->photo ? \App\Website\Services\ProfilePhotoUrl::get($dashboardMember->photo) : asset('images/profile_photos/boy.jpg') }}" alt="{{ $dashboardMember?->full_name ?? 'User' }}" width="60" height="60" loading="lazy" class="pqv-avatar" id="profileAvatar" />
          <span class="pqv-avatar-camera" aria-hidden="true"><i data-lucide="camera" width="11" height="11"></i></span>
        </div>
        <div class="pqv-user-text">
          <div class="pqv-name-row">
            <strong class="pqv-name">{{ $dashboardMember?->full_name ?? 'User' }}</strong>
            <span class="pqv-profile-id">{{ $dashboardMember?->profile_id ?? 'N/A' }}</span>
          </div>
          <span class="pqv-email">{{ $dashboardMember?->email ?? 'email@example.com' }}</span>
          <a href="{{route('view-my-profile')}}" class="pqv-link-btn">View Your Profile</a>
        </div>
      </div>

      <hr class="pqv-divider" />

      <div class="pqv-info-row">
        <div class="pqv-info-icon"><i data-lucide="wallet" width="22" height="22"></i></div>
        <div class="pqv-info-content">
          <strong class="pqv-info-title">Wallet</strong>
          <div class="pqv-info-meta-row">
            <span class="pqv-info-label">Wallet Balance</span>
            <strong class="pqv-info-value">₹{{ $dashboardMember?->wallet_balance ?? '0' }}</strong>
          </div>
          <a href="{{route('wallet.index')}}" class="pqv-link-btn">View Wallet</a>
        </div>
      </div>

      <hr class="pqv-divider" />

      <div class="pqv-info-row">
        <div class="pqv-info-icon"><i data-lucide="shield-check" width="22" height="22"></i></div>
        <div class="pqv-info-content">
          <div class="pqv-info-title-row">
            <strong class="pqv-info-title">Membership</strong>
            <span class="pqv-badge active">Active</span>
          </div>
          <div class="pqv-info-meta-row">
            <span class="pqv-info-label">Plan name</span>
            <strong class="pqv-info-value">{{ $dashboardPlan?->plan_name ?? 'Free' }}</strong>
          </div>
          <a href="{{route('memberships')}}" class="pqv-link-btn">View Membership Plans</a>
        </div>
      </div>

      <hr class="pqv-divider" />

      <div class="pqv-footer-links">
        <a href="{{route('member.terms-and-conditions')}}">Terms and Conditions</a>
        <span aria-hidden="true">•</span>
        <a href="{{route('member.privacy-policy')}}">Privacy Policy</a>
      </div>
    </div>
  </div>

  <!-- MAIN CONTENT -->
  <main id="main-content" class="main-content">
    @yield('content')
  </main>

  <!-- PAGE FOOTER -->
  <footer class="site-footer" role="contentinfo">
    <div class="container-xxl footer-inner">
      <div class="footer-brand">
        <img src="{{ asset($siteLogo) }}" alt="{{ $siteName }} Logo" class="footer-logo">
        <p class="footer-tagline">{{ $siteTagline }}</p>
      </div>
      <div class="footer-links">
        <a href="{{route('member.terms-and-conditions')}}">Terms &amp; Conditions</a>
        <a href="{{route('member.privacy-policy')}}">Privacy Policy</a>
        <a href="{{route('member.refund-policy')}}">Refund Policy</a>
        <a href="{{ url('/#contact') }}">Contact Us</a>
      </div>
      <p class="footer-copy">© 2026 {{ $siteName }}. All rights reserved.</p>
    </div>
  </footer>

  <!-- Rate Us Modal -->
  <div class="rate-modal-overlay" id="rateModal">
    <div class="rate-modal">

      <button class="rate-close" id="closeRateModal">
        &times;
      </button>

      <h2>Rate Us</h2>

      <div class="rating-stars" id="ratingStars">
        <span class="star" data-value="1">★</span>
        <span class="star" data-value="2">★</span>
        <span class="star" data-value="3">★</span>
        <span class="star" data-value="4">★</span>
        <span class="star" data-value="5">★</span>
      </div>

      <input type="hidden" id="ratingValue" value="0">

      <textarea
        id="review"
        placeholder="Enter your feedback here..."
        maxlength="500"></textarea>

      <button class="rate-submit">
        Submit
      </button>

    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('assets/js/toast-manager.js') }}"></script>
  <!-- Custom JS -->
  <script src="{{ asset('assets/js/script.js') }}"></script>
  <script>
    const uploadProfilePhotosUrl = "{{ route('profile.photo.update') }}";
  </script>
  <script src="{{ asset('assets/js/profile-photo.js') }}"></script>
  @yield('scripts')
</body>

</html>