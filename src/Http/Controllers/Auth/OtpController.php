<?php

namespace Auty\Http\Controllers\Auth;

use Auty\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function __construct(protected OtpService $otp) {}

    /**
     * Show the OTP verification form
     */
    public function index()
    {
        return view('auty::auth.otp');
    }

    /**
     * Verify the submitted OTP code
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:' . config('auty.otp.length', 6)],
        ]);

        $user = Auth::guard('admin')->user();

        if (!$this->otp->verify($user, $request->code)) {
            return back()->withErrors(['code' => 'Invalid or expired code. Please try again.']);
        }

        $request->session()->put('auty_otp_verified', true);

        return redirect()->intended(
            config('auty.auth.redirect_after_login', '/admin/dashboard')
        );
    }

    /**
     * Resend a new OTP to the user's email
     */
    public function resend(Request $request)
    {
        $user = Auth::guard('admin')->user();

        // Optional: simple rate limiting in controller (better with middleware)
        $lastSent = $request->session()->get('otp_last_sent_at');

        if ($lastSent && now()->diffInSeconds($lastSent) < 60) {
            return back()->with('warning', 'Please wait at least 60 seconds before requesting a new code.');
        }

        $user->generateOtp(); // Assuming this method sends the email

        $request->session()->put('otp_last_sent_at', now());

        return back()->with('success', 'A new OTP has been sent to your email.');
    }
}