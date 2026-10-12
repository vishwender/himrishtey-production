<?php

namespace App\Support;

use Illuminate\Http\Request;

final class SafeReturnUrl
{
    public static function resolve(mixed $url, Request $request, string $fallback): string
    {
        if (! is_string($url) || $url === '' || preg_match('/[\x00-\x20\x7f\\\\]/', $url)) {
            return $fallback;
        }

        // Root-relative paths stay on this application; network-path URLs do not.
        if (str_starts_with($url, '/') && ! str_starts_with($url, '//')) {
            return $url;
        }

        $parts = parse_url($url);
        if ($parts === false || isset($parts['user']) || isset($parts['pass'])) {
            return $fallback;
        }

        $scheme = strtolower($parts['scheme'] ?? '');
        $host = strtolower($parts['host'] ?? '');
        if (! in_array($scheme, ['http', 'https'], true)
            || $scheme !== strtolower($request->getScheme())
            || $host !== strtolower($request->getHost())) {
            return $fallback;
        }

        $port = $parts['port'] ?? ($scheme === 'https' ? 443 : 80);

        return $port === $request->getPort() ? $url : $fallback;
    }
}
