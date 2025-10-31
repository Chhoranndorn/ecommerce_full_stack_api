<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = [
        'app_name',
        'app_name_kh',
        'tagline',
        'logo',
        'splash_logo',
        'splash_background_color',
        'primary_color',
        'phone',
        'email',
        'address',
        'currency',
        'currency_symbol',
        'delivery_fee',
        'tax_rate',
        'app_version',
        'is_maintenance',
    ];

    protected $casts = [
        'delivery_fee' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'is_maintenance' => 'boolean',
    ];
}
