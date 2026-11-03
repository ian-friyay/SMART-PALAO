<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated. Please log in to proceed.',
                ], 401);
            }

            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        $user = auth()->user();

        // If the route specifically requires 'owner' and user is not owner
        if ($role === 'owner' && !$user->isOwner()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access Denied: Technical IoT telemetry and hardware controls are restricted to Farm Owners.',
                ], 403);
            }

            return redirect()->route('store')->with('error', 'Access restricted. Telemetry and controls are reserved for Incubation Admins.');
        }

        return $next($request);
    }
}
