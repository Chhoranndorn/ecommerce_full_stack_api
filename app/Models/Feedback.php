<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    protected $fillable = [
        'user_id',
        'rating',
        'message',
        'email',
        'allow_contact',
        'subscribe_newsletter',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
        'allow_contact' => 'boolean',
        'subscribe_newsletter' => 'boolean',
    ];

    /**
     * Get the user that submitted the feedback
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
