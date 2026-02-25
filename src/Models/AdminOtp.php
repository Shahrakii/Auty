<?php

namespace Auty\Models;

use Illuminate\Database\Eloquent\Model;

class AdminOtp extends Model
{
    protected $table    = 'admin_otps';
    protected $fillable = ['admin_id', 'code', 'used', 'attempts', 'expires_at'];
    protected $hidden   = ['code'];
    protected $casts    = ['used' => 'boolean', 'expires_at' => 'datetime'];

    public function isValid(string $code): bool
    {
        return !$this->used
            && !$this->expires_at->isPast()
            && $this->attempts < 3
            && hash_equals($this->code, $code);
    }

    public function markUsed(): void        { $this->update(['used' => true]); }
    public function incrementAttempts(): void { $this->increment('attempts'); }
}
