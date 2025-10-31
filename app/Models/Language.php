<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'name',
        'name_en',
        'code',
        'flag_icon',
        'is_default',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];
}
