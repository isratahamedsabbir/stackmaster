<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthCheckMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('web')->check()) {
            if(Auth::guard('web')->user()->hasRole('developer')) {
                return redirect()->route('developer.dashboard');
            }elseif(Auth::guard('web')->user()->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            } elseif(Auth::guard('web')->user()->hasRole('retailer')) {
                return redirect()->route('retailer.dashboard');
            } else{
                return redirect()->route('client.dashboard');
            }
        }
        return $next($request);
    }
}

