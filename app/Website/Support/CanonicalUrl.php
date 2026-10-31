<?php

namespace App\Website\Support;

use Illuminate\Http\Request;

class CanonicalUrl
{
    public static function baseUrl(): string
    {
        $baseUrl = rtrim((string) config('site.current.app_url', config('app.url')), '/');

        return match (parse_url($baseUrl, PHP_URL_HOST)) {
            'devbhoomirishtey.com', 'www.devbhoomirishtey.com' => 'https://www.devbhoomirishtey.com',
            'dogririshtey.com', 'www.dogririshtey.com' => 'https://dogririshtey.com',
            default => $baseUrl,
        };
    }

    public static function forRequest(Request $request): string
    {
        $url = self::baseUrl().'/'.ltrim($request->path(), '/');

        // Each blog listing page contains different posts; discard tracking parameters.
        $page = filter_var($request->query('page'), FILTER_VALIDATE_INT);
        if ($request->routeIs('blog.index') && $page !== false && $page > 1) {
            $url .= '?page='.$page;
        }

        return $url;
    }
}
