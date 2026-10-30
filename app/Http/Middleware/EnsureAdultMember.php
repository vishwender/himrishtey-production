<?php

namespace App\Http\Middleware;

use App\Support\AdultAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureAdultMember
{
    public function handle(Request $request, Closure $next): Response
    {
        // Preserve the deletion request path for already authenticated legacy accounts.
        if (AdultAccess::appliesTo($request) &&
            !$request->is('api/v1/profile/delete-request') &&
            !AdultAccess::isAdult($request->user()?->birth_date_time)) {
            return response()->json([
                'success' => false,
                'code' => 'ADULT_ACCESS_REQUIRED',
                'message' => AdultAccess::MESSAGE,
            ], 403);
        }
        return $next($request);
    }
}
