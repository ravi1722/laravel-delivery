<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class MenuItem extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'restaurant_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'image',
        'food_type',
        'is_available',
        'is_featured',
        'preparation_time',
        'calories',
        'allergens',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        // 'is_available'=> 'boolean',
        // 'is_featured'=> 'boolean',
        // 'allergens'=> 'array',
    ];

    // public function restaurant(): BelongsTo
    // {
    //     return $this->belongsTo(Restaurant::class);
    // }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'category_id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ItemVariant::class);
    }

    public function addons(): HasMany
    {
        return $this->hasMany(ItemAddon::class);
    }

    // scopes
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }

    // public function scopeVeg(Builder $query): Builder
    // {
    //     return $query->where('food_type', 'veg');
    // }

    // Accessors
    // public function finalPrice(): int
    // {
    //     return $this->discount_price ?? $this->price;
    // }

    public static function booted(): void
    {
        static::creating(function ($item) {
            $item->slug = Str::slug($item->name) . '-' . Str::random(5);
        });

        static::updating(function ($item) {
            if ($item->isDirty('name')) $item->slug = Str::slug($item->name) . '-' . Str::random(5);
        });
    }
}
