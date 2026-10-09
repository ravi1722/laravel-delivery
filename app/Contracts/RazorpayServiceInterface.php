<?php

namespace App\Contracts;

use App\Models\Order;
use App\Models\Payment;
use Eloquent;
use Illuminate\Database\Query\Builder;

interface RazorpayServiceInterface
{
    public function createOrder(Order $order): mixed;
    public function getKeyId(): string;
    public function capturePayment(
        string $razorpayOrderId,
        string $razorpayPaymentId,
        string $razorpaySignature
    ): Payment;
    public function handlePaymentFailure(
        string $razorpayOrderId,
        string $razorpayPaymentId,
        string $errorCode,
        string $errorDescription
    ): Payment;
    public function verifyPayment(
        string $razorpayOrderId,
        string $razorpayPaymentId,
        string $razorpaySignature
    ): bool;
}
