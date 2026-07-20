<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = ['owner_id', 'name', 'slug', 'description', 'logo', 'cover_image', 'cuisine_type', 'phone', 'email', 'address', 'city', 'state', 'pincode', 'latitude', 'longitude', 'commission_percentage', 'minimum_order', 'delivery_time', 'delivery_fee', 'rating', 'total_reviews', 'status', 'is_open', 'is_featured'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class, 'reviewable');
    }
}
