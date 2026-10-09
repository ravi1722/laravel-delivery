<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function initiatedBy()
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }
}
