<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceRouteRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return $next($request);
        }

        $roleByPrefix = [
            'principal' => ['principal'],
            'administrator' => ['administrator'],
            'teacher' => ['teacher'],
            'parent' => ['parent'],
        ];

        foreach ($roleByPrefix as $prefix => $allowedRoles) {
            if ($request->is($prefix) || $request->is($prefix . '/*')) {
                abort_unless(in_array($request->user()->user_type, $allowedRoles, true), 403, 'Unauthorized action.');
                break;
            }
        }

        return $next($request);
    }
}
