@if (trim((string) config('site.current.google_site_verification')) !== '')
    <meta name="google-site-verification" content="{{ config('site.current.google_site_verification') }}" />
@endif
