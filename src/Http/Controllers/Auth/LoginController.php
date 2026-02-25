<?php

namespace Auty\Http\Controllers\Auth;

use Auty\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->to(config('auty.auth.redirect_after_login', '/admin/dashboard'));
        }
        return view('auty::auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Rate limiting
        $key = 'auty_login_' . strtolower($request->email) . '_' . $request->ip();

        if (config('auty.throttle.enabled') && RateLimiter::tooManyAttempts($key, config('auty.throttle.max_attempts', 5))) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        $admin = Admin::where('email', $request->email)->first();

        if ($admin && $admin->isLocked()) {
            throw ValidationException::withMessages(['email' => 'This account is locked.']);
        }

        if (!Auth::guard('admin')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::hit($key, config('auty.throttle.decay_minutes', 15) * 60);
            if ($admin) $admin->incrementFailedLogin();
            throw ValidationException::withMessages(['email' => 'These credentials do not match our records.']);
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();
        Auth::guard('admin')->user()->recordSuccessfulLogin($request->ip());

        if (config('auty.otp.enabled')) {
            Auth::guard('admin')->user()->generateOtp();
            return redirect()->route('auty.otp.index');
        }

        return redirect()->to(config('auty.auth.redirect_after_login', '/admin/dashboard'));
    }
}
