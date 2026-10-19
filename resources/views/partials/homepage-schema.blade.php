@if ($siteKey === 'dogririshtey.com')
    @php
        $schemaHomeUrl = rtrim(config('site.sites')['dogririshtey.com']['app_url'] ?? 'https://dogririshtey.com', '/') . '/';
        $schemaLogoUrl = \Illuminate\Support\Str::startsWith($siteLogo, ['https://', 'http://'])
            ? $siteLogo
            : $schemaHomeUrl . ltrim($siteLogo, '/');
        $homepageSchema = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    '@id' => $schemaHomeUrl . '#organization',
                    'name' => $siteName,
                    'url' => $schemaHomeUrl,
                    'logo' => $schemaLogoUrl,
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => $schemaHomeUrl . '#website',
                    'name' => $siteName,
                    'url' => $schemaHomeUrl,
                    'description' => $siteMetaDescription,
                    'publisher' => ['@id' => $schemaHomeUrl . '#organization'],
                ],
            ],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($homepageSchema, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) !!}</script>
@endif
