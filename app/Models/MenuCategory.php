<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

class MenuCategory extends Model
{
    protected $fillable = ['restaurant_id', 'name', 'description', 'image', 'sort_order', 'is_active'];

    // protected $casts = ['is_active' => 'boolean'];

    // public function restraurant(): BelongsTo
    // {
    //     return $this->belongsTo(Restaurant::class);
    // }

    // public function items(): HasMany
    // {
    //     return $this->hasMany(MenuItem::class, 'category_id');
    // }

    // public function scopeActive(Builder $query): Builder
    // {
    //     return $query->where('is_active', true);
    // }
}
