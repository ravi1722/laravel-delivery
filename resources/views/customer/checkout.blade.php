@extends('layouts.app')

@section('title', 'Checkout')
@section('page-title', 'Checkout')
@push('styles')
    <style>
        .active-payment {
            border-color: #FF6B35 !important;
            background: rgba(255, 107, 53, .05);
        }
    </style>
@endpush

@section('content')
    <form method="POST" id="checkout-form" action="{{ route('customer.orders.store') }}">
        @csrf
        <div class="row g-4">
            {{-- Left --}}
            <div class="col-lg-8">
                {{-- Delivery Address --}}
                <div class="table-card p-4 mb-4">
                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-geo-alt me-2" style="color:#FF6B35"></i>
                        Delivery Address
                    </h6>

                    @if ($addresses->isEmpty())
                        <div class="alert alert-warning">
                            No address found.
                            <a href="{{ route('customer.addresses.index') }}">Add an address</a>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach ($addresses as $address)
                                <div class="col-md-6">
                                    <label class="address-option w-100" style="cursor:pointer">
                                        <input type="radio" name="address_id" value="{{ $address->id }}"
                                            {{ $address->is_default ? 'checked' : '' }} class="d-none address-radio">

                                        <div
                                            class="border rounded-3 p-3 address-card {{ $address->is_default ? 'border-primary bg-light' : '' }}">
                                            <div class="d-flex justify-content-between">
                                                <span class="badge" style="background:#FF6B35;font-size:11px">
                                                    {{ $address->label }}
                                                </span>
                                                @if ($address->is_default)
                                                    <span class="badge bg-primary" style="font-size:10px">
                                                        Default
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="mt-2 small text-dark">{{ $address->address_line1 }}</div>
                                            @if ($address->address_line2)
                                                <div class="small text-muted">{{ $address->address_line2 }}</div>
                                            @endif
                                            <div class="small text-muted">
                                                {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Payment Method --}}
                <div class="table-card p-4 mb-4">
                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-credit-card me-2" style="color:#FF6B35"></i>
                        Payment Method
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="w-100" style="cursor:pointer">
                                <input type="radio" name="payment_method" value="cod" checked class="d-none">
                                <div class="border rounded-3 p-3 text-center payment-option active-payment">
                                    <i class="bi bi-cash-coin fs-3 d-block mb-2" style="color:#28a745"></i>
                                    <div class="small fw-semibold">Cash on Delivery</div>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label class="w-100" style="cursor:pointer">
                                <input type="radio" name="payment_method" value="wallet" class="d-none">
                                <div class="border rounded-3 p-3 text-center payment-option">
                                    <i class="bi bi-wallet2 fs-3 d-block mb-2" style="color:#007bff"></i>
                                    <div class="small fw-semibold">Laravel-Delivery Wallet</div>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label class="w-100" style="cursor:pointer">
                                <input type="radio" name="payment_method" value="online" class="d-none">
                                <div class="border rounded-3 p-3 text-center payment-option">
                                    <i class="bi bi-phone fs-3 d-block mb-2" style="color:#FF6B35"></i>
                                    <div class="small fw-semibold">Online Payment</div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Special Instructions --}}
                <div class="table-card p-4 mb-4">
                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-chat-text me-2" style="color:#FF6B35"></i>
                        Special Instructions
                    </h6>
                    <textarea name="special_instructions" rows="3" class="form-control"
                        placeholder="Any special requests? (optional)"></textarea>
                </div>
            </div>

            {{-- Right — Order Summary --}}
            <div class="col-lg-4">
                <div class="table-card p-4 sticky-top" style="top:80px">
                    <h6 class="fw-semibold mb-3">Order Summary</h6>

                    {{-- Restaurant --}}
                    <div class="d-flex align-items-center gap-2 mb-3 pb-3 border-bottom">
                        <img src="{{ $restaurant->logo }}" width="36" height="36"
                            class="rounded-circle object-fit-cover">
                        <div>
                            <div class="small fw-semibold">{{ $restaurant->name }}</div>
                            <div class="text-muted" style="font-size:11px">{{ $restaurant->cuisine_type }}</div>
                        </div>
                    </div>

                    {{-- Cart Items --}}
                    @foreach ($cart['items'] as $item)
                        <div class="d-flex justify-content-between mb-2 small">
                            <span>{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                            <span>₹{{ number_format($item['total_price'], 0) }}</span>
                        </div>
                    @endforeach
                    <hr>

                    {{-- Coupon --}}
                    <div class="mb-3">
                        <div class="input-group input-group-sm">
                            <input type="text" id="couponInput" class="form-control" placeholder="Enter coupon code">
                            <button type="button" class="btn btn-outline-primary" id="applyCoupon">Apply</button>
                        </div>
                        <div id="couponMsg" class="mt-1 small"></div>
                        <input type="hidden" name="coupon_code" id="couponCode">
                    </div>
                    <hr>

                    {{-- Totals --}}
                    <div class="d-flex justify-content-between small mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span>₹{{ number_format($subtotal, 0) }}</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-2">
                        <span class="text-muted">Delivery Fee</span>
                        <span>₹{{ number_format($restaurant->delivery_fee, 0) }}</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-2">
                        <span class="text-muted">GST (5%)</span>
                        <span>₹{{ number_format($taxAmount, 0) }}</span>
                    </div>
                    <div id="discountRow" class="d-flex justify-content-between small mb-2 text-success d-none">
                        <span>Discount</span>
                        <span id="discountAmt">-₹0</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-semibold">
                        <span>Total</span>
                        <span id="grandTotal" style="color:#FF6B35">
                            ₹{{ number_format($total, 0) }}
                        </span>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3 py-2 fw-semibold">
                        <i class="bi bi-check-circle me-2"></i>Place Order
                    </button>
                    <div class="text-center mt-2">
                        <small class="text-muted">
                            Estimated delivery: {{ $restaurant->delivery_time }} minutes
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection


@push('scripts')
    <script>
        // Address selection highlight
        $('.address-radio').on('change', function() {
            $('.address-card').removeClass('border-primary bg-light');
            $(this).closest('.address-option').find('.address-card').addClass('border-primary bg-light');
        });

        $('.address-radio').on('click', function() {
            $(this).trigger('change');
        });

        // Payment method highlight
        $('input[name="payment_method"]').on('change', function() {
            $('.payment-option').removeClass('active-payment border-primary');
            $(this).closest('label').find('.payment-option').addClass('active-payment border-primary');
        });

        // Apply coupon
        $('#applyCoupon').on('click', function() {
            const code = $('#couponInput').val().trim();
            if (!code) return;

            $.post('{{ route('customer.orders.apply-coupon') }}', {
                coupon_code: code
            }, function(res) {
                if (res.success) {
                    $('#couponMsg').html(
                        `<span class="text-success"><i class="bi bi-check-circle"></i> ${res.message}</span>`
                        );
                    $('#couponCode').val(code);
                    $('#discountRow').removeClass('d-none');
                    $('#discountAmt').text(`-₹${res.discount}`);
                }
            }).fail(function(xhr) {
                $('#couponMsg').html(
                    `<span class="text-danger"><i class="bi bi-x-circle"></i> ${xhr.responseJSON?.message}</span>`
                    );
            });
        });
    </script>
@endpush
