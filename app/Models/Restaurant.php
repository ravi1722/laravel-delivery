<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;

class Restaurant extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'description',
        'logo',
        'cover_image',
        'cuisine_type',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'pincode',
        'latitude',
        'longitude',
        'commission_percentage',
        'minimum_order',
        'delivery_time',
        'delivery_fee',
        'rating',
        'total_reviews',
        'status',
        'is_open',
        'is_featured'
    ];

    protected $casts = [
        // 'is_open' => 'boolean',
        // 'is_featured' => 'boolean',
        // 'rating' => 'decimal:2'
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function menuCategories(): HasMany
    {
        return $this->hasMany(MenuCategory::class);
    }

    // public function menuItems(): HasMany
    // {
    //     return $this->hasMany(MenuItem::class);
    // }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    //------- scopes
    public function scopeInCity(Builder $query, string $city): Builder
    {
        return $query->where('city', $city);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('is_open', true);
    }

    // Accessors
    // public function logUrl(): string
    // {
    //     return $this->logo ? asset('storage/' . $this->logo) : asset('images/default-restaurant.png');
    // }

    // protected static function booted(): void
    // {
    //     static::creating(function ($restraurant) {
    //         if(empty($restraurant)) {
    //             $restraurant->slug = Str::slug($restraurant->name) . '-' . Str::random(5);
    //         }
    //     });
    // }
}
