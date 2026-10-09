<?php

namespace App\Services;

use App\Contracts\RazorpayServiceInterface;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

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

    public function capturePayment(
        string $razorpayOrderId,
        string $razorpayPaymentId,
        string $razorpaySignature
    ): Payment {

        // Verify signature
        if (!$this->verifyPayment($razorpayOrderId, $razorpayPaymentId, $razorpaySignature)) {
            throw new \Exception('Payment verification failed. Possible fraud attempt.');
        }

        // Get full payment details from Razorpay
        $payment = Payment::where('razorpay_order_id', $razorpayOrderId)->firstOrFail();

        // Update payment in DB (wrapped in transaction)
        $razorpayPayment = $this->api->payment->fetch($razorpayPaymentId);


        // Update payment in DB (wrapped in transaction)
        DB::transaction(function () use ($payment, $razorpayPayment, $razorpayPaymentId, $razorpaySignature) {
            $payment->update([
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature'  => $razorpaySignature,
                'status'              => 'paid',
                'method'              => $razorpayPayment['method'],
                'bank'                => $razorpayPayment['bank'] ?? null,
                'wallet'              => $razorpayPayment['wallet'] ?? null,
                'vpa'                 => $razorpayPayment['vpa'] ?? null,
                'card_network'        => $razorpayPayment['card']['network'] ?? null,
                'card_last4'          => $razorpayPayment['card']['last4'] ?? null,
                'razorpay_response'   => $razorpayPayment->toArray(),
                'paid_at'             => now(),
            ]);

            // Update order payment status
            $payment->order->update([
                'payment_status' => 'paid',
                'status'         => 'confirmed', // auto-confirm on payment
            ]);

            // Log status history
            $payment->order->statusHistories()->create([
                'status' => 'confirmed',
                'note'   => "Payment received via Razorpay ({$razorpayPayment['method']}). Payment ID: {$razorpayPaymentId}",
            ]);
        });

        Log::info('Payment captured successfully', [
            'order_id'            => $payment->order_id,
            'razorpay_payment_id' => $razorpayPaymentId,
            'amount'              => $payment->amount,
            'method'              => $razorpayPayment['method'],
        ]);

        return $payment->fresh();
    }

    public function handlePaymentFailure(
        string $razorpayOrderId,
        string $razorpayPaymentId,
        string $errorCode,
        string $errorDescription
    ): Payment {
        $payment = Payment::where('razorpay_order_id', $razorpayOrderId)->firstOrFail();
        $payment->update([
            'razorpay_payment_id' => $razorpayPaymentId,
            'status'              => 'failed',
            'failure_reason'      => "[{$errorCode}] {$errorDescription}",
        ]);

        $payment->order->update(['payment_status' => 'failed']);
        Log::warning('Payment failed', [
            'order_id'   => $payment->order_id,
            'error_code' => $errorCode,
            'error'      => $errorDescription,
        ]);
        return $payment->fresh();
    }

    public function verifyPayment(
        string $razorpayOrderId,
        string $razorpayPaymentId,
        string $razorpaySignature
    ): bool {
        try {
            // Razorpay signature verification
            // SHA256(razorpay_order_id + "|" + razorpay_payment_id, key_secret)
            $this->api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $razorpayOrderId,
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature'  => $razorpaySignature,
            ]);
            return true;
        } catch (SignatureVerificationError $e) {
            Log::warning('Razorpay signature verification failed', [
                'razorpay_order_id'   => $razorpayOrderId,
                'razorpay_payment_id' => $razorpayPaymentId,
                'error'               => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function getKeyId(): string
    {
        return config('razorpay.key_id');
    }
}
