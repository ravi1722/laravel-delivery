@extends('layouts.app')

@section('title', 'Manage Orders')
@section('page-title', 'Manage Orders')

@section('content')
    {{-- Filter Bar --}}
    <div class="table-card mb-4">
        <div class="p-3">
            <form method="GET" action="{{ route('restaurant.orders.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        @foreach (config('constants.order_status') as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="date" name="date" class="form-control form-control-sm"
                        value="{{ request('date', today()->toDateString()) }}">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('restaurant.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="table-card">
        <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-semibold mb-0">
                Orders
                <span class="badge bg-secondary ms-2">{{ $orders->total() }}</span>
            </h6>
            {{-- Live indicator --}}
            <div class="d-flex align-items-center gap-2 small text-success">
                <span class="spinner-grow spinner-grow-sm"></span>
                Live Updates Active
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0" id="ordersTable">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr id="order-row-{{ $order->id }}">
                            <td class="fw-semibold small">#{{ $order->order_number }}</td>
                            <td>
                                <div class="small fw-semibold">{{ $order->user->name }}</div>
                                <div class="text-muted" style="font-size:11px">
                                    {{ $order->user->phone }}
                                </div>
                            </td>
                            <td class="small">{{ $order->orderItems->count() }} items</td>
                            <td class="small fw-semibold">
                                ₹{{ number_format($order->total_amount, 0) }}
                            </td>
                            <td>
                                <span
                                    class="badge {{ $order->payment_method === 'cod' ? 'bg-warning text-dark' : 'bg-success' }} badge-status">
                                    {{ strtoupper($order->payment_method) }}
                                </span>
                            </td>
                            <td>
                                <span
                                    class="badge bg-{{ config('constants.status_colors')[$order->status] ?? 'secondary' }} badge-status"
                                    id="status-badge-{{ $order->id }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td class="small text-muted">
                                {{ $order->created_at->diffForHumans() }}
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('restaurant.orders.show', $order) }}"
                                        class="btn btn-xs btn-outline-primary" style="font-size:11px;padding:3px 8px">
                                        View
                                    </a>

                                    @php $nextStatus = config('constants.nextStatuses')[$order->status] ?? []; @endphp
                                    @foreach ($nextStatus as $status => $label)
                                        <button class="btn btn-xs btn-success update-status"
                                            data-order-id="{{ $order->id }}" data-status="{{ $status }}"
                                            style="font-size:11px;padding:3px 8px">
                                            {{ $label }} ✓
                                        </button>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-bag-x fs-2 d-block mb-2 opacity-25"></i>
                                No orders found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($orders->hasPages())
            <div class="p-4 border-top">
                {{ $orders->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $('.update-status').on('click', function() {
            const orderId = $(this).data('order-id');
            const status = $(this).data('status');
            const btn = $(this);

            // btn.prop('disabled', true).text('Updating...');

            $.post(`/restaurant/orders/${orderId}/status`, {
                    status: status,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                }, function(response) {
                    if (response.success) {
                        // Update status badge

                        let colors = @json(config('constants.colors'));
                        $(`#status-badge-${orderId}`)
                            .removeClass()
                            .addClass(`badge bg-${colors[status] || 'secondary'} badge-status`)
                            .text(status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()));
                        btn.closest('td').find('.update-status').remove();
                        showToast(response.message, 'success');
                    }
                })
                .fail(function(xhr) {
                    showToast(xhr.responseJSON?.message || 'Failed to update', 'danger');
                    btn.prop('disabled', false).text(btn.data('original-text'));
                });
        });

        function showToast(message, type) {
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

        // Real-time new order notification for restaurant
        // @if (auth()->user()->restaurant)
        //     window.Echo.private('restaurant.{{ auth()->user()->restaurant->id }}')
        //         .listen('.new.order.received', function(data) {
        //             playNotificationSound();
        //             showToast(`New Order #${data.order_number} received!`, 'warning');
        //             // Reload table after 3 seconds
        //             setTimeout(() => location.reload(), 3000);
        //         });
        // @endif
    </script>
@endpush
