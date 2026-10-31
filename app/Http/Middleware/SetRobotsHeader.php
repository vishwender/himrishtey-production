<?php

namespace App\Http\Middleware;

use App\Support\RobotsPolicy;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetRobotsHeader
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $response->headers->has('X-Robots-Tag')) {
            $response->headers->set(
                'X-Robots-Tag',
                $response->isSuccessful() ? RobotsPolicy::forRequest($request) : 'noindex, nofollow',
            );
        }

        return $response;
    }
}
