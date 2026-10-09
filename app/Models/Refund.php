<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = [
        'payment_id',
        'order_id',
        'initiated_by',
        'razorpay_refund_id',
        'razorpay_payment_id',
        'amount',
        'currency',
        'status',
        'type',
        'reason',
        'notes',
        'razorpay_response',
        'processed_at',
    ];
}
