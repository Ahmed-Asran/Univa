<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Log;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$role): Response
    {
        log::info('RoleMiddleware'. $role);
        $user = $request->user();
        log::info('RoleMiddleware checking user', [
        'user_id' => $user?->user_id ?? null,
        'email' => $user?->email ?? null,
        'roles' => $user?->roles->pluck('role_name')->toArray() ?? [],
        'required_role' => $role,
        'has_role' => $user?->hasRole($role) ?? null,
    ]);
    if ( !$user||!$user->hasRole($role)) {
        return response()->json(['message' => ' You are not authorized'], 403); // return response()->json(['message' => 'Unauthorized'], 403);
    }
    return $next($request);
    
    }
}
