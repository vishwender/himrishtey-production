<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureContentManagerAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->ensureRouteAccess($request);

        return $next($request);
    }

    public function ensureRouteAccess(Request $request): void
    {
        $admin = Auth::guard('admin')->user();

        if (! $admin || $admin->isSiteAdmin() || $admin->hasRole('super-admin') || ! $admin->hasRole('content-manager')) {
            return;
        }

        if (! $request->routeIs(
            'admin.settings.content.*',
            'admin.settings.password.*',
            'admin.site.select',
            'admin.site.switch',
            'admin.logout',
            'admin.blog-posts.*',
            'admin.success-stories.*',
            'admin.pages.*'
        )) {
            abort(403, 'Content managers can only access content management pages.');
        }

    }
}
