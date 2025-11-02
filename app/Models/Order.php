<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
     use HasFactory;

    protected $fillable = [
        'user_id',
        'delivery_method_id',
        'coupon_id',
        'coupon_code',
        'discount_amount',
        'status',
        'total',
        'subtotal',
        'delivery_fee',
        'shipping_address',
        'payment_method',
        'payment_status',
        'notes',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'total' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function deliveryMethod(): BelongsTo
    {
        return $this->belongsTo(DeliveryMethod::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }
}
