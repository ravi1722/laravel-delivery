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

            

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }

    public function failure() {}
}
