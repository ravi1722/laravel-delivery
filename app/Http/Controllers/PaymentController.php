<?php

namespace App\Http\Controllers;

use App\Contracts\RazorpayServiceInterface;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct(private RazorpayServiceInterface $razorpayService) {}
    public function show(Order $order)
    {
        if ($order->user_id != Auth::id()) {
            abort(403);
        }

        // Check order is in correct state
        if ($order->payment_status === 'paid') {
            return redirect()->route('customer.orders.show', $order)->with('info', 'This order is already paid.');
        }

        // Check if existing Razorpay order exists
        // (in case user is retrying after failure)
        $existingPayment = Payment::where('order_id', $order->id)
            ->whereIn('status', ['created', 'attempted'])
            ->latest()
            ->first();

        // $existingPayment = null;
        if ($existingPayment) {
            // Reuse existing Razorpay order
            $payment = $existingPayment;
        } else {
            $payment = $this->razorpayService->createOrder($order);
        }

        $order->load(['restaurant:id,name', 'orderItems', 'address']);

        return view('customer.payment', [
            'order'       => $order,
            'payment'     => $payment,
            'razorpayKey' => $this->razorpayService->getKeyId(),
        ]);
    }

    public function success(Request $request)
    {
        $request->validate([
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'required|string',
        ]);

        try {
            $payment = $this->razorpayService->capturePayment(
                $request->razorpay_order_id,
                $request->razorpay_payment_id,
                $request->razorpay_signature
            );

            // Fire order placed notifications
            event(new \App\Events\OrderPlaced($payment->order));

            return redirect()
                ->route('customer.orders.show', $payment->order)
                ->with('success', "Payment successful! Order #{$payment->order->order_number} confirmed. 🎉");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }

    public function failure(Request $request)
    {
        $request->validate([
            'razorpay_order_id'        => 'required|string',
            'razorpay_payment_id'      => 'required|string',
            'error_code'               => 'required|string',
            'error_description'        => 'required|string',
        ]);

        $payment = $this->razorpayService->handlePaymentFailure(
            $request->razorpay_order_id,
            $request->razorpay_payment_id,
            $request->error_code,
            $request->error_description
        );

        return redirect()
            ->route('payment.show', $payment->order)
            ->with('error', 'Payment failed: ' . $request->error_description . ' Please try again.');
    }

    public function status(Payment $payment)
    {
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        return view('customer.payment-status', compact('payment'));
    }
}
