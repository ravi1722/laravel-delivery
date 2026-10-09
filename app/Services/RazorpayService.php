<?php

namespace App\Services;

use App\Contracts\RazorpayServiceInterface;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;

class RazorpayService implements RazorpayServiceInterface
{
    /**
     * Create a new class instance.
     */
    private Api $api;
    public function __construct()
    {
        $this->api = new Api(
            config('razorpay.key_id'),
            config('razorpay.key_secret')
        );
    }

    public function createOrder(Order $order): mixed
    {
        // Amount in paise (multiply by 100)
        $amountInPaise = (int) ($order->total_amount * 100);
        try {
            // Create order in Razorpay
            $razorpayOrder = $this->api->order->create([
                'amount'          => $amountInPaise,
                'currency'        => config('razorpay.currency', 'INR'),
                'receipt'         => 'QB_' . $order->order_number,
                'partial_payment' => false,
                'notes'           => [
                    'order_id'       => $order->id,
                    'order_number'   => $order->order_number,
                    'customer_name'  => $order->user->name,
                    'customer_email' => $order->user->email,
                    'customer_phone' => $order->user->phone,
                    'restaurant'     => $order->restaurant->name,
                ],
            ]);

            // Save payment record in our DB
            $payment = Payment::create([
                'order_id'           => $order->id,
                'user_id'            => $order->user_id,
                'razorpay_order_id'  => $razorpayOrder['id'],
                'amount'             => $order->total_amount,
                'currency'           => config('razorpay.currency', 'INR'),
                'status'             => 'created',
                'razorpay_response'  => json_encode($razorpayOrder->toArray()),
            ]);

            // Update order with Razorpay order ID
            $order->update([
                'razorpay_order_id' => $razorpayOrder['id'],
                'payment_status'    => 'pending',
            ]);

            Log::info('Razorpay order created', [
                'order_id'          => $order->id,
                'razorpay_order_id' => $razorpayOrder['id'],
                'amount'            => $order->total_amount,
            ]);

            return $payment;
        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::error('Failed to create Razorpay order', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);
            throw new \Exception('Payment initialization failed. Please try again.');
        }
        return 1;
    }

    public function capturePayment(string $razorpayOrderId, string $razorpayPaymentId, string $razorpaySignature): Payment {}

    public function handlePaymentFailure(string $razorpayOrderId, string $razorpayPaymentId, string $errorCode, string $errorDescription): Payment {}

    public function getKeyId(): string
    {
        return config('razorpay.key_id');
    }
}
