<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = [
        'phone',
        'otp_code',
        'expires_at',
        'is_verified',
        'attempts',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function isExpired()
    {
        return $this->expires_at < now();
    }

    public function isVerified()
    {
        return $this->is_verified;
    }
}
