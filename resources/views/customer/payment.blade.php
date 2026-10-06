@extends('layouts.app')

@section('title', 'Pay for Order #' . $order->order_number)
@section('page-title', 'Complete Payment')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            {{-- Order Summary --}}
            <div class="table-card p-4 mb-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-bag me-2" style="color:#FF6B35"></i>
                    Order Summary
                </h6>

                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="{{ $order->restaurant->logo_url }}" width="48" height="48"
                        class="rounded-circle object-fit-cover border">
                    <div>
                        <div class="fw-semibold small">{{ $order->restaurant->name }}</div>
                        <div class="text-muted" style="font-size:12px">
                            Order #{{ $order->order_number }}
                        </div>
                    </div>
                </div>

                {{-- Items --}}
                @foreach ($order->orderItems as $item)
                    <div class="d-flex justify-content-between small py-1">
                        <span>{{ $item->item_name }} × {{ $item->quantity }}</span>
                        <span>₹{{ number_format($item->total_price, 0) }}</span>
                    </div>
                @endforeach

                <hr>

                <div class="d-flex justify-content-between small text-muted mb-1">
                    <span>Subtotal</span>
                    <span>₹{{ number_format($order->subtotal, 0) }}</span>
                </div>
                <div class="d-flex justify-content-between small text-muted mb-1">
                    <span>Delivery Fee</span>
                    <span>₹{{ number_format($order->delivery_fee, 0) }}</span>
                </div>
                <div class="d-flex justify-content-between small text-muted mb-1">
                    <span>Tax (GST 5%)</span>
                    <span>₹{{ number_format($order->tax_amount, 0) }}</span>
                </div>
                @if ($order->discount_amount > 0)
                    <div class="d-flex justify-content-between small text-success mb-1">
                        <span>Discount</span>
                        <span>-₹{{ number_format($order->discount_amount, 0) }}</span>
                    </div>
                @endif
                <hr>
                <div class="d-flex justify-content-between fw-semibold">
                    <span>Total Amount</span>
                    <span style="color:#FF6B35;font-size:20px">
                        ₹{{ number_format($order->total_amount, 0) }}
                    </span>
                </div>
            </div>


            {{-- Payment Methods Info --}}
            <div class="table-card p-4 mb-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-shield-check me-2 text-success"></i>
                    Secure Payment — Powered by Razorpay
                </h6>

                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-light text-dark border py-2 px-3">
                        <i class="bi bi-credit-card me-1"></i>Credit/Debit Card
                    </span>
                    <span class="badge bg-light text-dark border py-2 px-3">
                        <i class="bi bi-phone me-1"></i>UPI
                    </span>
                    <span class="badge bg-light text-dark border py-2 px-3">
                        <i class="bi bi-bank me-1"></i>Net Banking
                    </span>
                    <span class="badge bg-light text-dark border py-2 px-3">
                        <i class="bi bi-wallet2 me-1"></i>Wallets
                    </span>
                </div>

                {{-- Pay Now Button --}}
                <button id="payNowBtn" class="btn btn-primary w-100 py-3 fw-semibold fs-5">
                    <i class="bi bi-lock me-2"></i>
                    Pay ₹{{ number_format($order->total_amount, 0) }} Securely
                </button>

                <div class="text-center mt-3">
                    <small class="text-muted">
                        <i class="bi bi-shield-lock me-1"></i>
                        256-bit SSL Encrypted. Your payment is secure.
                    </small>
                </div>
            </div>

            {{-- Hidden forms for success/failure --}}
            <form id="successForm" method="POST" action="{{ route('payment.success') }}" class="d-none">
                @csrf
                <input type="hidden" name="razorpay_order_id" id="rzp_order_id">
                <input type="hidden" name="razorpay_payment_id" id="rzp_payment_id">
                <input type="hidden" name="razorpay_signature" id="rzp_signature">
            </form>

            <form id="failureForm" method="POST" action="{{ route('payment.failure') }}" class="d-none">
                @csrf
                <input type="hidden" name="razorpay_order_id" id="fail_order_id">
                <input type="hidden" name="razorpay_payment_id" id="fail_payment_id">
                <input type="hidden" name="error_code" id="fail_code">
                <input type="hidden" name="error_description" id="fail_description">
            </form>
        </div>
    </div>

@endsection
