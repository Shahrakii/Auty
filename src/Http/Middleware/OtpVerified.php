<?php

namespace Auty\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OtpVerified
{
    public function handle(Request $request, Closure $next)
    {
        if (!config('auty.otp.enabled')) {
            return $next($request);
        }

        if (!$request->session()->get('auty_otp_verified')) {
            return redirect()->route('auty.otp.index');
        }

        return $next($request);
    }
}
