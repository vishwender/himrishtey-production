<?php

namespace App\Http\Middleware;

use App\Services\AdminActivityLogger;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecordAdminActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        if ($request->isMethodSafe() || $response->getStatusCode() >= 400 || ! auth('admin')->check()) {
            return $response;
        }
        $newFlash = $request->session()->get('_flash.new', []);
        if (array_intersect(['errors', 'error'], $newFlash)) {
            return $response;
        }
        if ($response instanceof JsonResponse) {
            $result = $response->getData(true);
            if (is_array($result) && (($result['success'] ?? null) === false || ($result['status'] ?? null) === 'error')) {
                return $response;
            }
        }
        if (! $request->routeIs(
            'admin.staff-users.store', 'admin.staff-users.update', 'admin.staff-users.toggle-status', 'admin.staff-users.destroy',
            'admin.roles.update', 'admin.settings.password.update', 'admin.settings.content.update',
            'admin.blog-posts.*', 'admin.success-stories.*', 'admin.pages.update',
            'admin.members.store', 'admin.members.photos.*', 'admin.members.rotation.store', 'admin.rotations.complete',
            'admin.members.new.assign-staff', 'admin.members.relationship-manager.update', 'admin.members.membership.change'
        )) {
            return $response;
        }
        $parameters = collect($request->route()->parameters())->map(fn ($value) => $value instanceof Model ? $value->getKey() : $value)->all();
        $memberId = $parameters['memberId'] ?? $parameters['member'] ?? null;
        $route = $request->route()->getName();
        app(AdminActivityLogger::class)->log(
            action: str_replace('.', '_', substr($route, strlen('admin.'))),
            description: 'Completed '.str_replace(['admin.', '.', '-'], ['', ' ', ' '], $route).'.',
            module: explode('.', $route)[1],
            memberId: is_numeric($memberId) ? (int) $memberId : null,
            metadata: ['route' => $route, 'parameters' => $parameters, 'fields' => array_keys($request->except(['_token', '_method']))]
        );

        return $response;
    }
}
