<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Special extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'title_kh',
        'image',
        'description',
        'description_kh',
        'discount_percentage',
        'discount_type',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Check if promotion is currently valid
     */
    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();

        if ($this->valid_from && $now->isBefore($this->valid_from)) {
            return false;
        }

        if ($this->valid_until && $now->isAfter($this->valid_until)) {
            return false;
        }

        return true;
    }

    /**
     * Get formatted discount text
     */
    public function getDiscountTextAttribute()
    {
        if ($this->discount_type === 'percentage') {
            return $this->discount_percentage . '% OFF';
        } else {
            return '$' . $this->discount_percentage . ' OFF';
        }
    }

    /**
     * Scope for active promotions only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('valid_from')
                    ->orWhere('valid_from', '<=', Carbon::now());
            })
            ->where(function ($q) {
                $q->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', Carbon::now());
            });
    }
}
