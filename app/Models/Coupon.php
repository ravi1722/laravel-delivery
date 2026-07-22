<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'title',
        'description',
        'type',
        'value',
        'minimum_order',
        'maximum_discount',
        'usage_limit',
        'per_user_limit',
        'used_count',
        'is_active',
        'starts_at',
        'expires_at',
    ];

    // protected $casts = [
    //     'is_active' => 'boolean',
    //     'starts_at' => 'datetime',
    //     'expires_at' => 'datetime'
    // ];

    // public function usages(): HasMany
    // {
    //     return $this->hasMany(CouponUsage::class);
    // }

    // public function scopeActive(Builder $query): Builder
    // {
    //     return $query->where('active', true)->where(function ($q) {
    //         $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
    //     });
    // }
}
