<?php

namespace Auty\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('admin')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('auty.login')->with('error', 'Please log in to continue.');
        }

        $admin = Auth::guard('admin')->user();

        if ($admin->isLocked()) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            return redirect()->route('auty.login')->with('error', 'Your account is locked. Please contact support.');
        }

        if (!$admin->is_active) {
            Auth::guard('admin')->logout();
            return redirect()->route('auty.login')->with('error', 'Your account is inactive.');
        }

        return $next($request);
    }
}
