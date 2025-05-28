<?php

namespace App\Http\Middleware;

use App\Helpers\PermissionsHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (!PermissionsHelper::isSuperAdmin()) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
