@if (config('site.current.google_analytics_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('site.current.google_analytics_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() {
            window.dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', {{ Illuminate\Support\Js::from(config('site.current.google_analytics_id')) }});
    </script>
@endif
