<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Onboarding extends Model
{
    protected $fillable = [
        'title',
        'title_en',
        'description',
        'description_en',
        'image',
        'image_secondary',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
