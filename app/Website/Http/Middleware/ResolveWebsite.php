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

            return $next($request);
        } finally {
            config($original);
        }
    }
}
