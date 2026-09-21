<?php

namespace App\Website\Support;

use App\Models\Site;
use App\Services\SiteDatabaseService;
use Illuminate\Support\Facades\Config;

class SiteContext
{
    public static function normalizeHost(?string $host): string
    {
        $host = strtolower(trim((string) $host));
        $host = preg_replace('/:\d+$/', '', $host) ?? $host;

        return str_starts_with($host, 'www.') ? substr($host, 4) : $host;
    }

    /**
     * Resolve the current site configuration for a host.
     */
    public static function resolve(?string $host = null): ?array
    {
        $host = static::normalizeHost($host ?: request()->getHost());
        $sites = config('site.sites', []);

        foreach ($sites as $key => $site) {
            $hosts = array_map(
                [static::class, 'normalizeHost'],
                array_merge([$key], $site['hosts'] ?? [])
            );

            if (in_array($host, $hosts, true)) {
                return array_merge($site, ['key' => $key]);
            }
        }

        return null;
    }

    /**
     * Apply the site context to the current request.
     */
    public static function apply(?string $host = null): ?array
    {
        $site = static::resolve($host);

        if (! $site) {
            Config::set('site.current', null);

            return null;
        }

        $record = Site::query()
            ->where('code', $site['code'])
            ->where('status', true)
            ->first();
        abort_unless($record, 404, 'This site is unavailable.');
        app(SiteDatabaseService::class)->connect($record, strict: false);
        $connection = 'site';

        $resolvedHost = static::normalizeHost(
            $host ?: request()->getHost()
        );

        $appUrl = str_ends_with($resolvedHost, '.ddev.site')
            ? request()->getScheme().'://'.$resolvedHost
            : ($site['app_url'] ?? config('app.url'));

        $currentSite = array_merge($site, [
            'key' => $site['key'],
            'host' => $resolvedHost,
            'connection' => $connection,
            'app_url' => $appUrl,

            'name' => $site['name']
                ?? config('app.name'),

            'display_name' => $site['display_name']
                ?? $site['name']
                ?? config('app.name'),

            'logo' => $site['logo']
                ?? 'assets/images/himrishtey-logo.png',

            'tagline' => $site['tagline']
                ?? 'Connecting hearts across Himachal Pradesh & beyond.',

            'footer_text' => $site['footer_text']
                ?? 'Trusted matrimony services for families.',

            'primary_color' => $site['primary_color']
                ?? '#b92c3d',

            'secondary_color' => $site['secondary_color']
                ?? '#2f2d5c',

            'accent_color' => $site['accent_color']
                ?? '#f4c86c',

            'support_email' => $site['support_email']
                ?? null,

            'support_phone' => $site['support_phone']
                ?? null,

            'support_address' => $site['support_address']
                ?? null,

            'social' => $site['social']
                ?? [],

            'android_app_url' => $site['android_app_url']
                ?? null,

            'ios_app_url' => $site['ios_app_url']
                ?? null,

            'session_cookie' => $site['session_cookie']
                ?? sprintf(
                    '%s_session',
                    preg_replace('/[^a-z0-9]+/i', '_', $resolvedHost)
                ),
        ]);

        // The central default connection must remain unchanged for admin accounts and API applications.
        Config::set('site.current', $currentSite);
        Config::set('app.name', $currentSite['display_name']);

        if (! empty($currentSite['app_url'])) {
            Config::set('app.url', $currentSite['app_url']);
        }

        if (! empty($currentSite['session_cookie'])) {
            Config::set('session.cookie', $currentSite['session_cookie']);
        }

        return $currentSite;
    }
}
