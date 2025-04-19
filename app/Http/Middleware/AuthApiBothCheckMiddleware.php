<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthApiBothCheckMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('api')->check() && (Auth::guard('api')->user()->hasRole('user') || Auth::guard('api')->user()->hasRole('retailer'))) {
            return $next($request);
        }
        
        return response()->json([
            'status' => false,
            'error' => 'Unauthorized action.',
            'code' => 200
        ]);
    }
}