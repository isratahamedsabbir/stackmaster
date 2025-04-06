<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('web')->check()) {
            if (Auth::guard('web')->user()->hasRole('developer')) {
                return redirect()->intended(route('developer.dashboard', absolute: false));
            }elseif (Auth::guard('web')->user()->hasRole('admin')) {
                return redirect()->intended(route('admin.dashboard', absolute: false));
            } elseif (Auth::guard('web')->user()->hasRole('retailer')) {
                return redirect()->intended(route('retailer.dashboard', absolute: false));
            } elseif (Auth::guard('web')->user()->hasRole('client')) {
                return redirect()->intended(route('client.dashboard', absolute: false));
            }else{
                Auth::logout();
                return redirect()->intended(route('login', absolute: false));
            }
        }

        return redirect()->intended(route('home', absolute: false));
    }
}