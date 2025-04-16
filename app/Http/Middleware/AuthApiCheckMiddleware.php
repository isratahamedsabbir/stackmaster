<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthApiCheckMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('api')->check() && (Auth::guard('api')->user()->hasRole('owner') || Auth::guard('api')->user()->hasRole('renter'))) {
            return $next($request);
        }
        
        return response()->json([
            'status' => false,
            'error' => 'Unauthorized action.',
            'code' => 200
        ]);
    }
}