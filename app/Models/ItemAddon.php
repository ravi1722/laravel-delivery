<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemAddon extends Model
{
    protected $fillable = ['menu_item_id', 'name', 'price', 'is_available'];
}
