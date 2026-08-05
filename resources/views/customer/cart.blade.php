@extends('layouts.app')

@section('title', 'My Cart')
@section('page-title', 'My Cart')

@section('content')
    @if (empty($cart['items']))
        {{-- Empty Cart --}}
        <div class="table-card p-5 text-center">
            <i class="bi bi-cart-x" style="font-size:64px;color:#dee2e6"></i>
            <h5 class="fw-semibold mt-3 mb-2">Your cart is empty</h5>
            <p class="text-muted mb-4">
                Looks like you haven't added anything yet.
                Browse restaurants and add items to get started!
            </p>
            <a href="{{ route('customer.home') }}" class="btn btn-primary px-5">
                <i class="bi bi-shop me-2"></i>Browse Restaurants
            </a>
        </div>
    @else
        <div class="row g-4">
            {{-- Left — Cart Items --}}
            <div class="col-lg-8">
                {{-- Restaurant Info --}}
                @if ($restaurant)
                    <div class="table-card p-3 mb-4 d-flex align-items-center gap-3">
                        <img src="{{ $restaurant->logo }}" width="48" height="48"
                            class="rounded-circle object-fit-cover border">
                        <div class="flex-fill">
                            <div class="fw-semibold small">{{ $restaurant->name }}</div>
                            <div class="text-muted" style="font-size:12px">
                                {{ $restaurant->cuisine_type }} · {{ $restaurant->city }}
                            </div>
                        </div>
                        <form method="POST" action="{{ route('customer.cart.clear') }}"
                            onsubmit="return confirm('Clear entire cart?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash me-1"></i>Clear Cart
                            </button>
                        </form>
                    </div>
                @endif

                {{-- Cart Items List --}}
                <div class="table-card">

                    <div class="p-4 border-bottom">
                        <h6 class="fw-semibold mb-0">
                            Cart Items
                            <span class="badge bg-secondary ms-2">
                                {{ array_sum(array_column($cart['items'], 'quantity')) }} items
                            </span>
                        </h6>
                    </div>

                    @foreach ($cart['items'] as $cartItem)
                        <div class="p-4 border-bottom cart-item" id="cart-item-{{ $cartItem['cart_item_id'] }}">
                            <div class="d-flex gap-3">

                                {{-- Image --}}
                                @if ($cartItem['image'])
                                    <img src="{{ asset('storage/' . $cartItem['image']) }}" width="80" height="70"
                                        class="rounded-3 object-fit-cover flex-shrink-0">
                                @else
                                    <div class="rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center"
                                        style="width:80px;height:70px;background:#f8f9fa;color:#dee2e6">
                                        <i class="bi bi-egg-fried fs-3"></i>
                                    </div>
                                @endif

                                {{-- Details --}}
                                <div class="flex-fill">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            {{-- Food type indicator --}}
                                            <span class="me-1">
                                                @if ($cartItem['food_type'] === 'veg')
                                                    🟢
                                                @elseif($cartItem['food_type'] === 'non_veg')
                                                    🔴
                                                @else
                                                    🟡
                                                @endif
                                            </span>
                                            <span class="fw-semibold small">{{ $cartItem['name'] }}</span>

                                            @if (!empty($cartItem['variant_name']))
                                                <span class="badge bg-light text-dark ms-2" style="font-size:10px">
                                                    {{ $cartItem['variant_name'] }}
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Remove Button --}}
                                        <button class="btn btn-xs btn-outline-danger remove-item"
                                            data-id="{{ $cartItem['cart_item_id'] }}"
                                            style="font-size:11px;padding:3px 8px">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    {{-- Addons --}}
                                    @if (!empty($cartItem['addons']))
                                        <div class="text-muted mt-1" style="font-size:11px">
                                            + {{ collect($cartItem['addons'])->pluck('name')->implode(', ') }}
                                        </div>
                                    @endif

                                    {{-- Price + Quantity --}}
                                    <div class="d-flex align-items-center justify-content-between mt-2">
                                        <div style="color:#FF6B35" class="fw-semibold small">
                                            ₹{{ number_format($cartItem['unit_price'], 0) }} each
                                        </div>

                                        {{-- Quantity Controls --}}
                                        <div class="d-flex align-items-center gap-2">
                                            <button class="btn btn-sm btn-outline-secondary qty-btn" data-action="decrease"
                                                data-id="{{ $cartItem['cart_item_id'] }}"
                                                data-qty="{{ $cartItem['quantity'] }}"
                                                style="width:30px;height:30px;padding:0">
                                                <i class="bi bi-dash"></i>
                                            </button>

                                            <span class="fw-semibold" id="qty-{{ $cartItem['cart_item_id'] }}">
                                                {{ $cartItem['quantity'] }}
                                            </span>

                                            <button class="btn btn-sm btn-primary qty-btn" data-action="increase"
                                                data-id="{{ $cartItem['cart_item_id'] }}"
                                                data-qty="{{ $cartItem['quantity'] }}"
                                                style="width:30px;height:30px;padding:0">
                                                <i class="bi bi-plus"></i>
                                            </button>

                                            <span class="ms-3 fw-semibold small"
                                                id="item-total-{{ $cartItem['cart_item_id'] }}">
                                                ₹{{ number_format($cartItem['total_price'], 0) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Continue Shopping --}}
                <div class="mt-3">
                    <a href="{{ $restaurant ? route('customer.restaurant', $restaurant) : route('customer.home') }}"
                        class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>
                        Continue Shopping
                    </a>
                </div>
            </div>

            {{-- Right — Order Summary --}}
            <div class="col-lg-4">
                <div class="table-card p-4 sticky-top" style="top:80px">
                    <h6 class="fw-semibold mb-4">Order Summary</h6>

                    {{-- Price Breakdown --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="text-muted">
                                Subtotal
                                ({{ array_sum(array_column($cart['items'], 'quantity')) }} items)
                            </span>
                            <span id="summary-subtotal">
                                ₹{{ number_format($total, 0) }}
                            </span>
                        </div>

                        @if ($restaurant)
                            <div class="d-flex justify-content-between small mb-2">
                                <span class="text-muted">Delivery Fee</span>
                                <span>
                                    @if ($restaurant->delivery_fee > 0)
                                        ₹{{ number_format($restaurant->delivery_fee, 0) }}
                                    @else
                                        <span class="text-success fw-semibold">Free</span>
                                    @endif
                                </span>
                            </div>

                            <div class="d-flex justify-content-between small mb-2">
                                <span class="text-muted">GST (5%)</span>
                                <span>₹{{ number_format($total * 0.05, 0) }}</span>
                            </div>

                            @if ($restaurant->minimum_order > 0 && $total < $restaurant->minimum_order)
                                <div class="alert alert-warning py-2 mt-2 small">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Minimum order is ₹{{ number_format($restaurant->minimum_order, 0) }}.
                                    Add ₹{{ number_format($restaurant->minimum_order - $total, 0) }} more.
                                </div>
                            @endif
                        @endif
                    </div>
                    <hr>
                    {{-- Grand Total --}}
                    <div class="d-flex justify-content-between fw-semibold mb-4">
                        <span>Total</span>
                        <span style="color:#FF6B35;font-size:18px">
                            @if ($restaurant)
                                ₹{{ number_format($total + $restaurant->delivery_fee + $total * 0.05, 0) }}
                            @else
                                ₹{{ number_format($total, 0) }}
                            @endif
                        </span>
                    </div>

                    {{-- Checkout Button --}}
                    @if ($restaurant && $total >= $restaurant->minimum_order)
                        <a href="{{ route('customer.orders.checkout') }}" class="btn btn-primary w-100 py-2 fw-semibold">
                            <i class="bi bi-bag-check me-2"></i>
                            Proceed to Checkout
                        </a>
                    @else
                        <button class="btn btn-secondary w-100 py-2" disabled>
                            <i class="bi bi-bag-check me-2"></i>
                            Proceed to Checkout
                        </button>
                        @if ($restaurant && $total < $restaurant->minimum_order)
                            <div class="text-center text-muted mt-2" style="font-size:12px">
                                Add more items to reach minimum order
                            </div>
                        @endif
                    @endif

                    {{-- Savings Info --}}
                    @php
                        $savings = collect($cart['items'])->sum(function ($item) {
                            return 0; // can add discount calculation here
                        });
                    @endphp

                    <div class="text-center mt-3 text-muted" style="font-size:12px">
                        <i class="bi bi-shield-check text-success me-1"></i>
                        Safe & Secure Checkout
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        // Quantity buttons
        $(document).on('click', '.qty-btn', function() {
            const action = $(this).data('action');
            const id = $(this).data('id');
            const qty = parseInt($(this).data('qty'));
            const newQty = action === 'increase' ? qty + 1 : qty - 1;

            // Update data-qty on both buttons for this item
            $(`.qty-btn[data-id="${id}"]`).data('qty', newQty);

            $.ajax({
                url: `/cart/${id}`,
                type: 'PUT',
                data: {
                    quantity: newQty,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (newQty <= 0) {
                        // Remove the item row
                        $(`#cart-item-${id}`).fadeOut(300, function() {
                            $(this).remove();
                            // Reload if cart is now empty
                            if ($('.cart-item').length === 0) {
                                location.reload();
                            }
                        });
                    } else {
                        // Update quantity display
                        $(`#qty-${id}`).text(newQty);
                        // Reload to recalculate totals accurately
                        location.reload();
                    }
                },
                error: function() {
                    alert('Failed to update quantity. Please try again.');
                }
            });
        });

        // Remove item
        $(document).on('click', '.remove-item', function() {
            const id = $(this).data('id');

            $.ajax({
                url: `/cart/${id}`,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    $(`#cart-item-${id}`).fadeOut(300, function() {
                        $(this).remove();
                        if ($('.cart-item').length === 0) {
                            location.reload();
                        }
                    });
                }
            });
        });
    </script>
@endpush
