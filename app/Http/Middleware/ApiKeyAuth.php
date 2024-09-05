<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => 'Unauthorized access. Authorization required.'
            ], 401);
        }

        return $next($request);
    }
}