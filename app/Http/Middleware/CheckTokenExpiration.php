<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->user()->currentAccessToken();

        if ($token->expires_at && Carbon::now()->greaterThan($token->expires_at)) {
            // Revoke the expired token
            $token->delete();

            return response()->json([
                'status' => 'error',
                'message' => 'Token has expired. Please login again.',
            ], 401);
        }

        return $next($request);
    }
}
