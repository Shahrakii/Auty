<?php

namespace Auty\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auty::auth.forgot-password');
    }

    public function send(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $status = Password::broker('admins')->sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Password reset link sent! Check your email.')
            : back()->withErrors(['email' => 'No admin account found with that email address.']);
    }
}
