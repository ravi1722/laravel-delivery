@extends('layouts.app')

@section('title', $restaurant->name)
@section('page-title', $restaurant->name)

@section('content')
    <div class="row g-4">
        {{-- Left — Menu --}}
        <div class="col-lg-8">
            {{-- Restaurant Info --}}
            <div class="table-card mb-4 overflow-hidden">
                @if ($restaurant->cover_image)
                    <img src="{{ asset('storage/' . $restaurant->cover_image) }}" class="w-100 object-fit-cover"
                        style="height:200px">
                @endif
                <div class="p-4">
                    <div class="d-flex align-items-start gap-3">
                        <img src="{{ $restaurant->logo }}" width="64" height="64"
                            class="rounded-3 border object-fit-cover">
                        <div class="flex-fill">
                            <h4 class="fw-bold mb-1">{{ $restaurant->name }}</h4>
                            <div class="text-muted small mb-2">{{ $restaurant->cuisine_type }}</div>
                            <div class="d-flex flex-wrap gap-3" style="font-size:13px">
                                <span class="text-warning fw-semibold">
                                    <i class="bi bi-star-fill"></i>
                                    {{ number_format($restaurant->rating, 1) }}
                                    ({{ $restaurant->reviews_count }} reviews)
                                </span>
                                <span class="text-muted">
                                    <i class="bi bi-clock me-1"></i>{{ $restaurant->delivery_time }} min
                                </span>
                                <span class="text-muted">
                                    <i class="bi bi-truck me-1"></i>
                                    @if ($restaurant->delivery_fee > 0)
                                        ₹{{ number_format($restaurant->delivery_fee, 0) }} delivery
                                    @else
                                        Free delivery
                                    @endif
                                </span>
                                <span class="text-muted">
                                    <i class="bi bi-bag me-1"></i>
                                    Min ₹{{ number_format($restaurant->minimum_order, 0) }}
                                </span>
                            </div>
                        </div>
                        <span class="badge {{ $restaurant->is_open ? 'bg-success' : 'bg-danger' }} badge-status">
                            {{ $restaurant->is_open ? '🟢 Open' : '🔴 Closed' }}
                        </span>
                    </div>
                </div>
            </div>
            {{-- Category Tabs --}}
            @if ($categories->count() > 0)
                <div class="table-card mb-3">
                    <div class="p-3 overflow-auto">
                        <div class="d-flex gap-2 flex-nowrap">
                            @foreach ($categories as $index => $category)
                                <button
                                    class="btn btn-sm category-tab {{ $index === 0 ? 'btn-primary' : 'btn-outline-secondary' }} flex-shrink-0"
                                    data-category="{{ $category->id }}">
                                    {{ $category->name }}
                                    <span class="badge bg-light text-dark ms-1">
                                        {{ $category->items_count }}
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Menu Items --}}
                @foreach ($categories as $category)
                    <div class="menu-section mb-4" id="category-{{ $category->id }}">
                        <h6 class="fw-semibold mb-3 px-1">{{ $category->name }}</h6>
                        <div class="table-card">
                            @foreach ($category->items as $item)
                                <div class="p-3 border-bottom menu-item-row" data-category="{{ $category->id }}">
                                    <div class="d-flex gap-3">
                                        {{-- Image --}}
                                        @if ($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" width="90" height="80"
                                                class="rounded-3 object-fit-cover flex-shrink-0">
                                        @else
                                            <div class="rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center"
                                                style="width:90px;height:80px;background:#f8f9fa;color:#dee2e6">
                                                <i class="bi bi-egg-fried fs-3"></i>
                                            </div>
                                        @endif

                                        {{-- Details --}}
                                        <div class="flex-fill">
                                            <div class="d-flex align-items-start justify-content-between">
                                                <div>
                                                    {{-- Veg/Non-veg indicator --}}
                                                    <span class="me-1" title="{{ $item->food_type }}">
                                                        @if ($item->food_type === 'veg')
                                                            🟢
                                                        @elseif($item->food_type === 'non_veg')
                                                            🔴
                                                        @else
                                                            🟡
                                                        @endif
                                                    </span>
                                                    <span class="fw-semibold small">{{ $item->name }}</span>
                                                    @if ($item->is_featured)
                                                        <span class="badge ms-1" style="background:#FF6B35;font-size:9px">
                                                            ⭐ Popular
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="text-end">
                                                    @if ($item->discount_price)
                                                        <div class="fw-bold small" style="color:#FF6B35">
                                                            ₹{{ number_format($item->discount_price, 0) }}
                                                        </div>
                                                        <div class="text-muted text-decoration-line-through"
                                                            style="font-size:11px">
                                                            ₹{{ number_format($item->price, 0) }}
                                                        </div>
                                                    @else
                                                        <div class="fw-bold small" style="color:#FF6B35">
                                                            ₹{{ number_format($item->price, 0) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            @if ($item->description)
                                                <p class="text-muted mb-1" style="font-size:12px;line-height:1.4">
                                                    {{ Str::limit($item->description, 80) }}
                                                </p>
                                            @endif

                                            <div class="d-flex align-items-center justify-content-between mt-2">
                                                <div class="text-muted" style="font-size:11px">
                                                    @if ($item->preparation_time)
                                                        ⏱ {{ $item->preparation_time }} min
                                                    @endif
                                                    @if ($item->calories)
                                                        · {{ $item->calories }} cal
                                                    @endif
                                                </div>

                                                @auth
                                                    @if ($restaurant->is_open)
                                                        <button class="btn btn-sm btn-primary add-to-cart"
                                                            style="font-size:12px;padding:4px 12px"
                                                            data-item-id="{{ $item->id }}"
                                                            data-item-name="{{ $item->name }}"
                                                            data-item-price="{{ $item->final_price }}">
                                                            <i class="bi bi-plus-lg"></i> Add
                                                        </button>
                                                    @else
                                                        <span class="text-muted small">Restaurant closed</span>
                                                    @endif
                                                @else
                                                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary"
                                                        style="font-size:12px">
                                                        Login to order
                                                    </a>
                                                @endauth
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        </div>


        {{-- Right — Cart Summary --}}
        @auth
            <div class="col-lg-4">
                <div class="table-card p-4 sticky-top" style="top:80px">
                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-bag me-2"></i>Your Cart
                    </h6>
                    <div id="cart-items">
                        @if (empty($cart['items']))
                            <div class="text-center text-muted py-3">
                                <i class="bi bi-bag-x fs-2 d-block mb-2 opacity-25"></i>
                                <small>Your cart is empty</small>
                            </div>
                        @else
                            @foreach ($cart['items'] as $cartItem)
                                <div class="d-flex justify-content-between align-items-center mb-2 py-2 border-bottom"
                                    id="cart-item-{{ $cartItem['cart_item_id'] }}">
                                    <div class="flex-fill">
                                        <div class="small fw-semibold">{{ $cartItem['name'] }}</div>
                                        @if ($cartItem['variant_name'])
                                            <div class="text-muted" style="font-size:11px">
                                                {{ $cartItem['variant_name'] }}
                                            </div>
                                        @endif
                                        <div class="small" style="color:#FF6B35">
                                            ₹{{ number_format($cartItem['unit_price'], 0) }}
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <button class="btn btn-xs btn-outline-secondary qty-btn" data-action="decrease"
                                            data-id="{{ $cartItem['cart_item_id'] }}" data-qty="{{ $cartItem['quantity'] }}"
                                            style="width:24px;height:24px;padding:0;font-size:14px">
                                            -
                                        </button>
                                        <span class="small fw-semibold">{{ $cartItem['quantity'] }}</span>
                                        <button class="btn btn-xs btn-primary qty-btn" data-action="increase"
                                            data-id="{{ $cartItem['cart_item_id'] }}" data-qty="{{ $cartItem['quantity'] }}"
                                            style="width:24px;height:24px;padding:0;font-size:14px">
                                            +
                                        </button>
                                    </div>
                                </div>
                            @endforeach

                            <div class="d-flex justify-content-between py-2 fw-semibold">
                                <span>Total</span>
                                <span
                                    id="cart-total">₹{{ number_format(array_sum(array_column($cart['items'], 'total_price')), 0) }}</span>
                            </div>
                            <a href="{{ route('customer.orders.checkout') }}" class="btn btn-primary w-100 mt-2">
                                Proceed to Checkout
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endauth

    </div>
@endsection

@push('scripts')
    <script>
        $('.category-tab').on('click', function() {
            let categoryId = $(this).data('category');
            $('.category-tab').removeClass('btn-primary').addClass('btn-outline-secondary');
            $(this).addClass('btn-primary').removeClass('btn-outline-secondary');
            $(`#category-${categoryId}`)[0].scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });

        $('.add-to-cart').on('click', function() {
            const button = $(this);
            const itemId = button.data('item-id');
            const itemName = button.data('item-id');
            button.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i>');

            $.post('{{ route('customer.cart.add') }}', {
                menu_item_id: itemId,
                quantity: 1
            }, function(response) {
                if (response.success) {
                    console.log("success  => ", response);
                    // Update cart count in navbar
                    $('#notificationCount').text(response.cart_count);
                    showToast(`${itemName} added to cart!`, 'success');

                    // Refresh cart sidebar
                    location.reload();
                }
            }).fail(function(xhr) {
                console.log("fail ", xhr);
                const msg = xhr.responseJSON?.message || 'Failed to add item';
                showToast(msg, 'danger');
            }).always(function() {
                btn.prop('disabled', false).html('<i class="bi bi-plus-lg"></i> Add');
            });
        });

        // Qty buttons
        $(document).on('click', '.qty-btn', function() {
            const action = $(this).data('action');
            const id = $(this).data('id');
            const qty = parseInt($(this).data('qty'));
            const newQty = action === 'increase' ? qty + 1 : qty - 1;

            $.ajax({
                url: `/cart/${id}`,
                type: 'PUT',
                data: {
                    quantity: newQty >= 0 ? newQty : 0
                },
                success: function(response) {
                    location.reload();
                }
            });
        });

        // Toast helper
        function showToast(message, type = 'success') {
            const toast = `<div class="position-fixed bottom-0 end-0 p-3" style="z-index:9999">
                <div class="toast show align-items-center text-white bg-${type} border-0">
                    <div class="d-flex">
                        <div class="toast-body small">${message}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto"
                                onclick="this.closest('.position-fixed').remove()"></button>
                    </div>
                </div>
            </div>`;
            $('body').append(toast);
            setTimeout(() => $('.position-fixed').last().remove(), 3000);
        }
    </script>
@endpush
