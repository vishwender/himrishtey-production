<?php

namespace App\Website\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireWebsite
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless(config('site.current'), 404);

        return $next($request);
    }
}
