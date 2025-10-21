<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Get all permissions through roles
        $userPermissions = $request->user()
            ->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->unique()
            ->toArray();

        if (!in_array($permission, $userPermissions)) {
            return response()->json(['message' => 'Unauthorized. You do not have permission to access this resource.'], 403);
        }

        return $next($request);
    }
}