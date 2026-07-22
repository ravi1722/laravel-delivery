<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'restaurant_id',
        'address_id',
        'delivery_agent_id',
        'coupon_id',
        'status',
        'payment_method',
        'payment_status',
        'subtotal',
        'delivery_fee',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'special_instructions',
        'estimated_delivery_at',
        'delivered_at',
    ];

    protected $casts = [
        'estimated_delivery_at' => 'datetime',
        'delivered_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'total_amount' => 'decimal:2'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    // public function address(): BelongsTo
    // {
    //     return $this->belongsTo(Address::class);
    // }

    // public function deliveryAgent(): BelongsTo
    // {
    //     return $this->belongsTo(DeliveryAgent::class);
    // }

    // public function orderItems(): HasMany
    // {
    //     return $this->hasMany(OrderItem::class);
    // }

    // public function statusHistories(): HasMany
    // {
    //     return $this->hasMany(OrderStatusHistory::class);
    // }

    // public function coupon(): BelongsTo
    // {
    //     return $this->belongsTo(Coupon::class);
    // }

    // public function review(): HasOne
    // {
    //     return $this->hasOne(Review::class);
    // }

    // scopes
    // public function scopeForUser(Builder $query, int $user_id): Builder
    // {
    //     return $query->where('user_id', '=', $user_id);
    // }

    // public function scopeForRestraurant(Builder $query, int $restraurant_id): Builder
    // {
    //     return $query->where('restaurant_id', $restraurant_id);
    // }

    // public function scopeForStatus(Builder $query, string $status): Builder
    // {
    //     return $query->where('status', $status);
    // }

    // Helpers
    // public function isDelivered(): bool
    // {
    //     return $this->status === 'delivered';
    // }

    // public function isCancelled(): bool
    // {
    //     return $this->status === 'cancelled';
    // }

    // public function canBeCancelled(): bool
    // {
    //     return in_array($this->status, ['placed', 'confirmed']);
    // }
}
