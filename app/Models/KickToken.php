<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KickToken extends Model
{
    protected $fillable = [
        'user_id',
        'access_token',
        'refresh_token',
        'expires_in',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function isExpired(): bool
    {
        return !$this->expires_at || $this->expires_at->isPast();
    }

    public static function calculateExpiry(int $expiresIn): Carbon
    {
        return now()->addSeconds($expiresIn - 60); // Refresh 1 min before expiry
    }
}
