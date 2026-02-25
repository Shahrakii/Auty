<?php

namespace Auty\Services;

use Auty\Models\Admin;
use Auty\Models\AdminOtp;
use Auty\Notifications\OtpNotification;

class OtpService
{
    public function generate(Admin $admin): AdminOtp
    {
        $admin->otps()->where('used', false)->update(['used' => true]);

        $length  = config('auty.otp.length', 6);
        $expires = config('auty.otp.expires_in', 10);
        $code    = str_pad((string) random_int(0, (int) str_repeat('9', $length)), $length, '0', STR_PAD_LEFT);

        $otp = $admin->otps()->create([
            'code'       => $code,
            'used'       => false,
            'attempts'   => 0,
            'expires_at' => now()->addMinutes($expires),
        ]);

        $admin->notify(new OtpNotification($code));

        return $otp;
    }

    public function verify(Admin $admin, string $code): bool
    {
        $otp = $admin->otps()
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otp) return false;

        $otp->incrementAttempts();

        if ($otp->isValid($code)) {
            $otp->markUsed();
            return true;
        }

        return false;
    }
}
