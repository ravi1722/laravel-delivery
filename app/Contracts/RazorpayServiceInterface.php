<?php

namespace App\Contracts;

use App\Models\Order;
use Eloquent;
use Illuminate\Database\Query\Builder;

interface RazorpayServiceInterface
{
    public function createOrder(Order $order): mixed;
    public function getKeyId(): string;
}
