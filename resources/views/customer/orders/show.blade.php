@extends('layouts.app')

@section('title', 'Order #' . $order->order_number)
@section('page-title', 'Order Details')

@section('content')
    <div class="row g-4">
        <div class="col-lg-8">
            {{-- Status Timeline --}}
            <div class="table-card p-4 mb-4">
                <h6 class="fw-semibold mb-4">Order Status</h6>
                @php
                    $currentIndex = array_search($order->status, $allStatuses);
                @endphp
                @if ($order->status !== 'cancelled')
                    <div class="d-flex align-items-center justify-content-between">
                        @foreach ($allStatuses as $index => $status)
                            @php
                                $isDone = $currentIndex !== false && $index <= $currentIndex;
                                $isCurrent = $index === $currentIndex;
                            @endphp
                            <div class="text-center flex-fill">
                                <div class="mx-auto mb-2 rounded-circle d-flex align-items-center justify-content-center"
                                    style="width:40px;height:40px;
                                background:{{ $isDone ? '#FF6B35' : '#f8f9fa' }};
                                color:{{ $isDone ? '#fff' : '#adb5bd' }};
                                border: 2px solid {{ $isDone ? '#FF6B35' : '#dee2e6' }}">
                                    <i class="bi {{ $statusLabels[$status]['icon'] ?? 'bi-circle' }}"
                                        style="font-size:16px"></i>
                                </div>
                                <div
                                    style="font-size:10px;
                                color:{{ $isDone ? '#FF6B35' : '#adb5bd' }};
                                font-weight:{{ $isCurrent ? '700' : '400' }}">
                                    {{ $statusLabels[$status]['label'] }}
                                </div>
                            </div>
                            @if (!$loop->last)
                                <div class="flex-fill"
                                    style="height:2px;
                                background:{{ $index < $currentIndex ? '#FF6B35' : '#dee2e6' }};
                                margin-top:-20px;max-width:40px">
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
            </div>
            {{-- Order Items --}}
            <div class="table-card p-4 mb-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-bag me-2"></i>Order Items
                </h6>
                @foreach ($order->orderItems as $item)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <div class="small fw-semibold">{{ $item->item_name }}</div>
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
                        </div>
                        <div class="text-end">
                            <div class="small text-muted">x{{ $item->quantity }}</div>
                            <div class="small fw-semibold" style="color:#FF6B35">
                                ₹{{ number_format($item->total_price, 0) }}
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Totals --}}
                <div class="pt-3">
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
                        <span>Total</span>
                        <span style="color:#FF6B35">
                            ₹{{ number_format($order->total_amount, 0) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Cancel Button --}}
            @if ($order->canBeCancelled())
                <div class="table-card p-4">
                    <h6 class="fw-semibold mb-3 text-danger">Cancel Order</h6>
                    <form method="POST" action="{{ route('customer.orders.cancel', $order) }}"
                        onsubmit="return confirm('Are you sure you want to cancel?')">
                        @csrf
                        <div class="mb-3">
                            <input type="text" name="reason" class="form-control form-control-sm"
                                placeholder="Reason for cancellation" required>
                        </div>
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="bi bi-x-circle me-1"></i>Cancel Order
                        </button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Right Side --}}
        <div class="col-lg-4">
            {{-- Order Info --}}
            <div class="table-card p-4 mb-4">
                <h6 class="fw-semibold mb-3">Order Info</h6>
                <div class="mb-2 small">
                    <span class="text-muted">Order Number</span>
                    <div class="fw-semibold">#{{ $order->order_number }}</div>
                </div>
                <div class="mb-2 small">
                    <span class="text-muted">Placed On</span>
                    <div class="fw-semibold">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                </div>
                <div class="mb-2 small">
                    <span class="text-muted">Payment Method</span>
                    <div class="fw-semibold text-capitalize">
                        {{ str_replace('_', ' ', $order->payment_method) }}
                    </div>
                </div>
                <div class="mb-2 small">
                    <span class="text-muted">Payment Status</span>
                    <div>
                        <span
                            class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }} badge-status">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Delivery Address --}}
            <div class="table-card p-4 mb-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-geo-alt me-2"></i>Delivery Address
                </h6>
                <div class="small">
                    <div class="fw-semibold">{{ $order->address->label }}</div>
                    <div class="text-muted">{{ $order->address->address_line1 }}</div>
                    @if ($order->address->address_line2)
                        <div class="text-muted">{{ $order->address->address_line2 }}</div>
                    @endif
                    <div class="text-muted">
                        {{ $order->address->city }}, {{ $order->address->state }}
                        - {{ $order->address->pincode }}
                    </div>
                </div>
            </div>

            {{-- Restaurant --}}
            <div class="table-card p-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-shop me-2"></i>Restaurant
                </h6>
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ $order->restaurant->logo }}" width="48" height="48"
                        class="rounded-3 object-fit-cover border">
                    <div class="small">
                        <div class="fw-semibold">{{ $order->restaurant->name }}</div>
                        <div class="text-muted">{{ $order->restaurant->address }}</div>
                        <div class="text-muted">{{ $order->restaurant->phone }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
