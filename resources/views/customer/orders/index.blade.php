@extends('layouts.app')

@section('title', 'My Orders')
@section('page-title', 'My Orders')

@section('content')
    <div class="table-card">
        <div class="p-4 border-bottom">
            <h6 class="fw-semibold mb-0">Order History</h6>
        </div>
        @forelse($orders as $order)
            <div class="p-4 border-bottom">
                <div class="d-flex align-items-start gap-3">
                    <img src="{{ $order->restaurant->logo ?? asset('images/default-restaurant.png') }}" width="50"
                        height="50" class="rounded-3 object-fit-cover flex-shrink-0 border">
                    <div class="flex-fill">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-semibold small">{{ $order->restaurant->name }}</div>
                                <div class="text-muted" style="font-size:12px">
                                    #{{ $order->order_number }}
                                    · {{ $order->created_at->format('d M Y, h:i A') }}
                                </div>
                                <div class="text-muted mt-1" style="font-size:12px">
                                    {{ $order->orderItems->count() }} item(s)
                                    · ₹{{ number_format($order->total_amount, 0) }}
                                </div>
                            </div>
                            <div class="text-end">
                                <span
                                    class="badge bg-{{ config('constants.status_colors')[$order->status] ?? 'secondary' }} badge-status d-block mb-2">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                                <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-xs btn-outline-primary"
                                    style="font-size:11px;padding:3px 10px">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-5 text-center text-muted">
                <i class="bi bi-bag-x fs-1 d-block mb-3 opacity-25"></i>
                No orders yet.
                <a href="{{ route('customer.home') }}" class="d-block mt-2">Browse restaurants</a>
            </div>
        @endforelse
        @if ($orders->hasPages())
            <div class="p-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection
