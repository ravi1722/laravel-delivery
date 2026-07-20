<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function restaurant() {
        return $this->belongsTo(Restaurant::class);
    }
}
