<?php

namespace Auty\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Admin extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table = 'admins';
    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'is_active',
        'is_locked',
        'locked_until',
        'failed_login_count',
        'last_login_at',
        'last_login_ip',
        'email_verified_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'locked_until'      => 'datetime',
        'is_active'         => 'boolean',
        'is_locked'         => 'boolean',
    ];

    // ── Relationships ─────────────────────────────
    public function otps()
    {
        return $this->hasMany(AdminOtp::class, 'admin_id');
    }

    // ── Helpers ───────────────────────────────────
    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    public function isLocked(): bool
    {
        if (!$this->is_locked) return false;
        if ($this->locked_until && $this->locked_until->isPast()) {
            $this->unlock();
            return false;
        }
        return true;
    }

    public function lock(int $minutes = null): void
    {
        $this->update([
            'is_locked'    => true,
            'locked_until' => $minutes ? now()->addMinutes($minutes) : null,
        ]);
    }

    public function unlock(): void
    {
        $this->update([
            'is_locked'          => false,
            'locked_until'       => null,
            'failed_login_count' => 0,
        ]);
    }

    public function incrementFailedLogin(): void
    {
        $this->increment('failed_login_count');
        $max = config('auty.throttle.max_attempts', 5);
        if ($this->failed_login_count >= $max && config('auty.throttle.lock_account', true)) {
            $this->lock(config('auty.throttle.lock_duration_minutes', 30));
        }
    }

    public function recordSuccessfulLogin(string $ip): void
    {
        $this->update([
            'failed_login_count' => 0,
            'last_login_at'      => now(),
            'last_login_ip'      => $ip,
            'is_locked'          => false,
            'locked_until'       => null,
        ]);
    }

    public function generateOtp(): AdminOtp
    {
        return app(\Auty\Services\OtpService::class)->generate($this);
    }

    public function verifyOtp(string $code): bool
    {
        return app(\Auty\Services\OtpService::class)->verify($this, $code);
    }

    public function sendPasswordResetNotification($token): void
    {
        $url = route('auty.password.reset', [
            'token' => $token,
            'email' => $this->email,
        ]);

        $this->notify(new \Auty\Notifications\ResetPasswordNotification($url));
    }
}
