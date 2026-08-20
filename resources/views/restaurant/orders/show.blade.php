@extends('layouts.app')

@section('title', 'Order #' . $order->order_number)
@section('page-title', 'Order Details')

@section('content')

    <div class="row g-4">

        {{-- Left Column --}}
        <div class="col-lg-8">

            {{-- Order Status Update Card --}}
            @if (!in_array($order->status, ['delivered', 'cancelled']))
                <div class="table-card p-4 mb-4">
                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-arrow-right-circle me-2" style="color:#FF6B35"></i>
                        Update Order Status
                    </h6>
                    @php $available = config('constants.nextStatuses')[$order->status] ?? []; @endphp
                    @if (!empty($available))
                        <div class="d-flex gap-3 flex-wrap">
                            @foreach ($available as $status => $config)
                                <form method="POST" action="{{ route('restaurant.orders.update-status', $order) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="{{ $status }}">
                                    <button type="submit" class="btn btn-{{ $config['color'] }} px-4">
                                        <i class="bi {{ $config['icon'] }} me-2"></i>
                                        {{ $config['label'] }}
                                    </button>
                                </form>
                            @endforeach

                            {{-- Cancel option --}}
                            @if (in_array($order->status, ['placed', 'confirmed']))
                                <form method="POST" action="{{ route('restaurant.orders.update-status', $order) }}"
                                    onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                    @csrf
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="btn btn-outline-danger px-4">
                                        <i class="bi bi-x-circle me-2"></i>Cancel Order
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            {{-- Status Timeline --}}
            <div class="table-card p-4 mb-4">
                <h6 class="fw-semibold mb-4">
                    <i class="bi bi-clock-history me-2"></i>
                    Order Timeline
                </h6>
                @php $currentIndex = array_search($order->status, config('constants.order_status')); @endphp

                @if ($order->status !== 'cancelled')
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        @foreach (config('constants.order_status') as $index => $status)
                            @php
                                $isDone = $currentIndex !== false && $index <= $currentIndex;
                                $isCurrent = $index === $currentIndex;
                            @endphp
                            <div class="text-center flex-fill">
                                <div class="mx-auto mb-2 rounded-circle d-flex align-items-center justify-content-center"
                                    style="width:44px;height:44px;
                                background:{{ $isDone ? '#FF6B35' : '#f8f9fa' }};
                                color:{{ $isDone ? '#fff' : '#adb5bd' }};
                                border:2px solid {{ $isDone ? '#FF6B35' : '#dee2e6' }};
                                transition: all .3s">
                                    <i class="bi {{ $statusLabels[$status]['icon'] }}" style="font-size:18px"></i>
                                </div>
                                <div
                                    style="font-size:10px;
                                color:{{ $isDone ? '#FF6B35' : '#adb5bd' }};
                                font-weight:{{ $isCurrent ? '700' : '400' }}">
                                    {{ $statusLabels[$status]['label'] }}
                                </div>
                            </div>
                            @if (!$loop->last)
                                <div
                                    style="flex:1;height:2px;
                                background:{{ $index < $currentIndex ? '#FF6B35' : '#dee2e6' }};
                                margin-top:-24px;
                                max-width:50px;
                                transition: all .3s">
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-danger mb-0">
                        <i class="bi bi-x-circle me-2"></i>
                        This order was cancelled.
                    </div>
                @endif

                {{-- Status History Log --}}
                @if ($order->statusHistories->count() > 0)
                    <div class="border-top pt-3 mt-3">
                        <div class="small fw-semibold text-muted mb-2">Status Log</div>
                        @foreach ($order->statusHistories as $history)
                            <div class="d-flex gap-2 mb-2 small">
                                <span class="text-muted" style="min-width:130px">
                                    {{ $history->created_at->format('d M, h:i A') }}
                                </span>
                                <span class="badge bg-secondary badge-status">
                                    {{ ucfirst(str_replace('_', ' ', $history->status)) }}
                                </span>
                                @if ($history->note)
                                    <span class="text-muted">— {{ $history->note }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Order Items --}}
            <div class="table-card p-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-bag me-2"></i>
                    Order Items
                    <span class="badge bg-secondary ms-2">
                        {{ $order->orderItems->count() }} items
                    </span>
                </h6>

                @foreach ($order->orderItems as $item)
                    <div
                        class="d-flex justify-content-between align-items-center
                        py-3 border-bottom">
                        <div class="d-flex gap-3 align-items-center">
                            {{-- Item Image --}}
                            @if ($item->menuItem?->image)
                                <img src="{{ asset('storage/' . $item->menuItem->image) }}" width="50" height="44"
                                    class="rounded-3 object-fit-cover flex-shrink-0">
                            @else
                                <div class="rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center"
                                    style="width:50px;height:44px;
                                    background:#f8f9fa;color:#dee2e6">
                                    <i class="bi bi-egg-fried"></i>
                                </div>
                            @endif

                            <div>
                                <div class="fw-semibold small">{{ $item->item_name }}</div>
                                @if ($item->variant_name)
                                    <div class="text-muted" style="font-size:11px">
                                        {{ $item->variant_name }}
                                    </div>
                                @endif
                                @if ($item->addons)
                                    <div class="text-muted" style="font-size:11px">
                                        + {{ collect($item->addons)->pluck('name')->implode(', ') }}
                                    </div>
                                @endif
                                <div class="text-muted" style="font-size:11px">
                                    ₹{{ number_format($item->unit_price, 0) }} × {{ $item->quantity }}
                                </div>
                            </div>
                        </div>
                        <div class="fw-semibold small" style="color:#FF6B35">
                            ₹{{ number_format($item->total_price, 0) }}
                        </div>
                    </div>
                @endforeach

                {{-- Price Summary --}}
                <div class="pt-3">
                    <div class="d-flex justify-content-between small text-muted mb-2">
                        <span>Subtotal</span>
                        <span>₹{{ number_format($order->subtotal, 0) }}</span>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-2">
                        <span>Delivery Fee</span>
                        <span>₹{{ number_format($order->delivery_fee, 0) }}</span>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-2">
                        <span>Tax (GST 5%)</span>
                        <span>₹{{ number_format($order->tax_amount, 0) }}</span>
                    </div>
                    @if ($order->discount_amount > 0)
                        <div class="d-flex justify-content-between small text-success mb-2">
                            <span>Discount</span>
                            <span>-₹{{ number_format($order->discount_amount, 0) }}</span>
                        </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between fw-semibold">
                        <span>Total</span>
                        <span style="color:#FF6B35;font-size:16px">
                            ₹{{ number_format($order->total_amount, 0) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">
            {{-- Order Info --}}
            <div class="table-card p-4 mb-4">
                <h6 class="fw-semibold mb-3">Order Info</h6>

                <div class="mb-3 small">
                    <span class="text-muted d-block">Order Number</span>
                    <span class="fw-semibold fs-6">#{{ $order->order_number }}</span>
                </div>

                <div class="mb-3 small">
                    <span class="text-muted d-block">Placed On</span>
                    <span class="fw-semibold">
                        {{ $order->created_at->format('d M Y, h:i A') }}
                    </span>
                </div>

                <div class="mb-3 small">
                    <span class="text-muted d-block">Estimated Delivery</span>
                    <span class="fw-semibold">
                        {{ $order->estimated_delivery_at?->format('h:i A') ?? 'N/A' }}
                    </span>
                </div>

                <div class="mb-3 small">
                    <span class="text-muted d-block">Payment Method</span>
                    <span class="fw-semibold text-capitalize">
                        {{ str_replace('_', ' ', $order->payment_method) }}
                    </span>
                </div>

                <div class="mb-3 small">
                    <span class="text-muted d-block">Payment Status</span>
                    <span
                        class="badge
                    {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}
                    badge-status">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>

                <div class="small">
                    <span class="text-muted d-block">Current Status</span>
                    <span
                        class="badge bg-{{ config('constants.status_colors')[$order->status] ?? 'secondary' }} badge-status">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>
            </div>

            {{-- Customer Info --}}
            <div class="table-card p-4 mb-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-person me-2"></i>Customer
                </h6>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="{{ $order->user->avatar }}" width="44" height="44"
                        class="rounded-circle object-fit-cover border">
                    <div class="small">
                        <div class="fw-semibold">{{ $order->user->name }}</div>
                        <div class="text-muted">{{ $order->user->phone }}</div>
                        <div class="text-muted">{{ $order->user->email }}</div>
                    </div>
                </div>
            </div>

            {{-- Delivery Address --}}
            <div class="table-card p-4 mb-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-geo-alt me-2"></i>Delivery Address
                </h6>
                <div class="small">
                    <div class="badge mb-2" style="background:#FF6B35;font-size:11px">
                        {{ $order->address->label }}
                    </div>
                    <div class="fw-semibold">{{ $order->address->address_line1 }}</div>
                    @if ($order->address->address_line2)
                        <div class="text-muted">{{ $order->address->address_line2 }}</div>
                    @endif
                    <div class="text-muted">
                        {{ $order->address->city }},
                        {{ $order->address->state }} —
                        {{ $order->address->pincode }}
                    </div>
                </div>
            </div>

            {{-- Special Instructions --}}
            @if ($order->special_instructions)
                <div class="table-card p-4">
                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-chat-text me-2"></i>Special Instructions
                    </h6>
                    <div class="small text-muted fst-italic">
                        "{{ $order->special_instructions }}"
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Real-time status updates for restaurant owner
        @if (auth()->user()->restaurant)
            document.addEventListener('DOMContentLoaded', function() { //must need bcas app.js we have main variable
                window.Echo.private('restaurant.{{ auth()->user()->restaurant->id }}')
                    .listen('.new.order.received', function(data) {
                        playNotificationSound();
                    });
            });
        @endif
    </script>
@endpush
