<?php

namespace App\Website\Http\Middleware;

use App\Website\Support\SiteContext;
use Closure;
use Illuminate\Http\Request;

class ResolveWebsite
{
    public function handle(Request $request, Closure $next)
    {
        // Admin selection and API app codes have their own tenant resolvers.
        if ($request->is('admin', 'admin/*', 'shared-profile/*', 'up')) {
            return $next($request);
        }

        $original = collect(['site.current', 'app.name', 'app.url', 'session.cookie', 'session.domain', 'services.google.redirect'])
            ->mapWithKeys(fn ($key) => [$key => config($key)])->all();
        try {
            SiteContext::apply($request->getHost());
            if (config('site.current')) {
                // A member session must never be shared between the four domains.
                config([
                    'session.domain' => null,
                    'services.google.redirect' => $request->getSchemeAndHttpHost().'/google-signup-callback',
                ]);
            }

            $response = $next($request);
            if (config('site.current') && str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
                // HTML includes session-specific CSRF tokens and must not be served from a shared/stale cache.
                $response->headers->set('Cache-Control', 'private, no-store, max-age=0');
            }

            return $response;
        } finally {
            config($original);
        }
    }
}
