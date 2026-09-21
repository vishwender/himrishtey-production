<header class="nav public-nav public-header">

    {{-- Logo --}}
    <a
        class="logo public-logo"
        href="{{ route('welcome') }}"
        aria-label="{{ $siteName }} home">
        <img
            src="{{ asset($siteLogo) }}"
            alt="{{ $siteName }}">
    </a>


    {{-- Desktop Navigation --}}
    <nav aria-label="Main navigation">

        <a href="{{ route('welcome') }}#featured">
            Discover
        </a>

        <a href="{{ route('welcome') }}#how">
            How it Works
        </a>

        <a href="{{ route('welcome') }}#stories">
            Success Stories
        </a>

        <a href="{{ route('about-us') }}">
            Why {{ $siteName }}
        </a>

        <a href="{{ route('blog.index') }}">
            Matrimony Guide
        </a>

    </nav>


    {{-- Header Actions --}}
    <div class="public-actions header-actions">

        {{-- Theme Toggle --}}
        <button
            class="public-theme-toggle header-theme-toggle header-icon-button"
            type="button"
            data-public-theme
            aria-label="Switch color theme"
            title="Switch theme">
            <i
                class="theme-sun"
                data-lucide="sun"
                aria-hidden="true"></i>

            <i
                class="theme-moon"
                data-lucide="moon"
                aria-hidden="true"></i>
        </button>


        {{-- Guest Actions --}}
        @guest('member')

        <a
            class="outline public-outline public-cta public-cta-secondary"
            href="{{ route('login-form') }}">
            Login
        </a>

        <a
            class="solid public-solid public-cta public-cta-primary header-register-link"
            href="{{ route('login-form') }}#register">
            Register Free
        </a>

        @endguest


        {{-- Logged In Member --}}
        @auth('member')

        <a
            class="solid public-solid public-cta public-cta-primary"
            href="{{ route('home') }}">
            My Account
        </a>

        @endauth


        {{-- Mobile Menu --}}
        <button
            class="public-menu mobile-menu-toggle"
            type="button"
            data-public-menu
            aria-label="Open navigation"
            aria-expanded="false"
            aria-controls="public-mobile-navigation">
            <svg class="public-menu-icon" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" aria-hidden="true" focusable="false">
                <path d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

    </div>

</header>


{{-- Mobile Navigation --}}
<nav
    id="public-mobile-navigation"
    class="public-mobile-nav"
    data-public-mobile
    aria-label="Mobile navigation">

    <a href="{{ route('welcome') }}#featured">
        Matches
    </a>

    <a href="{{ route('welcome') }}#search">
        Search
    </a>

    <a href="{{ route('success-stories') }}">
        Success Stories
    </a>

    <a href="{{ route('pricing') }}">
        Membership
    </a>

    <a href="{{ route('about-us') }}">
        About Us
    </a>

    <a href="{{ route('blog.index') }}">
        Blog
    </a>

    @guest('member')

    <a
        class="mobile-auth-link"
        href="{{ route('login-form') }}">
        Login
    </a>

    <a
        class="mobile-auth-link mobile-register-link"
        href="{{ route('login-form') }}#register">
        Register Free
    </a>

    @endguest

</nav>
