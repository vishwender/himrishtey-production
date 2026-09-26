<!doctype html>
<html lang="en" data-site="{{ $siteKey }}">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('assets/js/csrf.js') }}?v={{ filemtime(public_path('assets/js/csrf.js')) }}"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', e($siteMetaTitle))</title>

    <meta name="description" content="@yield('description', e($siteMetaDescription))">

    {{-- Theme must be applied before page renders --}}
    <script>
        try {
            document.documentElement.dataset.theme =
                localStorage.getItem('site-theme') ||
                localStorage.getItem('public-theme') ||
                (
                    matchMedia('(prefers-color-scheme: dark)').matches ?
                    'dark' :
                    'light'
                );
        } catch (e) {}
    </script>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    {{-- All public CSS + JS through Vite --}}
    @vite([
    'resources/css/home/home.css',
    'resources/js/home.js'
    ])

    @stack('head')
    @include('partials.google-analytics')
</head>

<body
    class="public-site tenant-{{ \Illuminate\Support\Str::slug($siteKey) }}"
    style="
        --brand: {{ $sitePrimaryColor }};
        --deep: {{ $siteSecondaryColor }};
        --gold: {{ $siteAccentColor }};
    ">

    @include('partials.public-header')

    <main class="@yield('main-class', 'public-main')">
    @yield('content')
    </main>

    @include('partials.public-footer')

    @stack('scripts')

</body>

</html>
