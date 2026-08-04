<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'menu_item_id', 'variant_id', 'item_name', 'variant_name', 'unit_price', 'quantity', 'total_price', 'addons'];
}
