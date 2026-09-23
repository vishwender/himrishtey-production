<!doctype html>
<html lang="en" data-site="{{ $siteKey }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('assets/js/csrf.js') }}?v={{ filemtime(public_path('assets/js/csrf.js')) }}"></script>
    <title>@yield('title', e($siteMetaTitle))</title>
    <meta name="description" content="@yield('description', e($siteMetaDescription))">
    <script>
        try {
            const savedTheme = localStorage.getItem('site-theme') || localStorage.getItem('public-theme') || (matchMedia('(prefers-color-scheme:dark)').matches ? 'dark' : 'light');
            document.documentElement.dataset.theme = savedTheme;
        } catch (e) {}
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="module">
        import {
            createIcons,
            icons
        } from 'https://cdn.jsdelivr.net/npm/lucide@0.468.0/+esm';

        const initializeLucideIcons = () => createIcons({
            icons
        });

        window.lucide = window.lucide || {};
        window.lucide.createIcons = initializeLucideIcons;
        document.addEventListener('DOMContentLoaded', initializeLucideIcons);
        initializeLucideIcons();
    </script>
    <link rel="stylesheet" href="{{ asset('assets/css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/privacy-policies.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/success-stories.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/toast-manager.css') }}">
    @yield('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/public-shell.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/public-footer.css') }}?v={{ filemtime(public_path('assets/css/public-footer.css')) }}">
    <link rel="stylesheet" href="{{ asset('assets/css/public-cta.css') }}?v=20260903">
    <link rel="stylesheet" href="{{ asset('assets/css/public-navigation.css') }}?v={{ filemtime(public_path('assets/css/public-navigation.css')) }}">
    <style>
        :root {
            --site-primary: {{ $sitePrimaryColor ?? '#b92c3d' }};

            --site-secondary: {{ $siteSecondaryColor ?? '#2f2d5c' }};

            --site-accent: {{ $siteAccentColor ?? '#f4c86c' }};
            --brand: var(--site-primary);
            --deep: var(--site-secondary);
            --gold: var(--site-accent);
        }
    </style>
    @include('partials.google-analytics')
</head>

<body class="public-site tenant-{{ \Illuminate\Support\Str::slug($siteKey) }}">
    @include('partials.public-header')
    <main class="public-content">@yield('content')</main>
    @include('partials.public-footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/toast-manager.js') }}"></script>
    <script src="{{ asset('assets/js/login.js') }}?v=20260905-profile-id"></script>
    <script>
        document.querySelector('[data-public-theme]')?.addEventListener('click', () => {
            const n = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
            document.documentElement.dataset.theme = n;
            localStorage.setItem('public-theme', n)
        });
        window.lucide?.createIcons();
    </script>
    <script type="module">
        import { initializeNavigation } from '{{ asset('assets/js/public-navigation.js') }}?v={{ filemtime(public_path('assets/js/public-navigation.js')) }}';
        initializeNavigation();
    </script>
    @yield('scripts')
</body>

</html>
