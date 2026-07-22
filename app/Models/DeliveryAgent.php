<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use League\Uri\Builder;

class DeliveryAgent extends Model
{
    protected $fillable = [
        'user_id',
        'vehicle_type',
        'vehicle_number',
        'license_number',
        'current_latitude',
        'current_longitude',
        'status',
        'total_earnings',
        'total_deliveries',
        'rating',
        'is_approved',
    ];

    // protected $casts = [
    //     'is_approved' => 'boolean'
    // ];

    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(User::class);
    // }

    // public function orders(): HasMany
    // {
    //     return $this->hasMany(Order::class);
    // }

    // public function review(): MorphMany
    // {
    //     return $this->morphMany(Review::class, 'reviewable');
    // }

    // public function scopeAvailable(Builder $query): Builder
    // {
    //     return $query->where('status', 'available')->where('is_approved', true);
    // }
}
