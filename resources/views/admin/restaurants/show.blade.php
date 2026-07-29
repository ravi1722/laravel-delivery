@extends('layouts.app')

@section('title', $restaurant->name)
@section('page-title', $restaurant->name)

@section('content')
    <div class="row g-4">
        {{-- Left --}}
        <div class="col-lg-8">

            {{-- Restaurant Info --}}
            <div class="table-card mb-4 overflow-hidden">
                @if ($restaurant->cover_image)
                    <img src="{{ asset('storage/' . $restaurant->cover_image) }}" class="w-100 object-fit-cover"
                        style="height:180px">
                @else
                    <div class="w-100 d-flex align-items-center justify-content-center"
                        style="height:180px;background:linear-gradient(135deg,#1a1a2e,#0f3460)">
                        <i class="bi bi-shop text-white" style="font-size:64px;opacity:.3"></i>
                    </div>
                @endif
                <div class="p-4">
                    <div class="d-flex align-items-start gap-3">
                        <img src="{{ $restaurant->logo }}" width="64" height="64"
                            class="rounded-3 border object-fit-cover flex-shrink-0">
                        <div class="flex-fill">
                            <h5 class="fw-bold mb-1">{{ $restaurant->name }}</h5>
                            <div class="text-muted small mb-2">{{ $restaurant->cuisine_type }}</div>
                            <div class="d-flex flex-wrap gap-3 small">
                                <span><i class="bi bi-geo-alt me-1"></i>{{ $restaurant->city }},
                                    {{ $restaurant->state }}</span>
                                <span><i class="bi bi-telephone me-1"></i>{{ $restaurant->phone }}</span>
                                @if ($restaurant->email)
                                    <span><i class="bi bi-envelope me-1"></i>{{ $restaurant->email }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-end">
                            <span
                                class="badge bg-{{ config('constants.colors')[$restaurant->status] }} badge-status d-block mb-2">
                                {{ ucfirst($restaurant->status) }}
                            </span>
                            <span class="badge {{ $restaurant->is_open ? 'bg-success' : 'bg-danger' }} badge-status">
                                {{ $restaurant->is_open ? 'Open' : 'Closed' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card text-center">
                        <div class="stat-value">{{ $restaurant->orders_count }}</div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card text-center">
                        <div class="stat-value">{{ $restaurant->reviews_count }}</div>
                        <div class="stat-label">Total Reviews</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card text-center">
                        <div class="stat-value">{{ $restaurant->menu_items_count }}</div>
                        <div class="stat-label">Menu Items</div>
                    </div>
                </div>
            </div>

            {{-- Menu Categories --}}
            <div class="table-card mb-4">
                <div class="p-4 border-bottom">
                    <h6 class="fw-semibold mb-0">Menu Categories</h6>
                </div>
                @forelse($restaurant->menuCategories as $category)
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <div class="fw-semibold small">{{ $category->name }}</div>
                        <span class="badge bg-secondary badge-status">
                            {{ $category->items->count() }} items
                        </span>
                    </div>
                @empty
                    <div class="p-4 text-center text-muted small">No menu categories yet</div>
                @endforelse
            </div>

            {{-- Recent Orders --}}
            <div class="table-card">
                <div class="p-4 border-bottom">
                    <h6 class="fw-semibold mb-0">Recent Orders</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($restaurant->orders as $order)
                                <tr>
                                    <td class="small fw-semibold">#{{ $order->order_number }}</td>
                                    <td class="small">{{ $order->user->name ?? 'N/A' }}</td>
                                    <td class="small">₹{{ number_format($order->total_amount, 0) }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ config('constants.status_colors')[$order->status] ?? 'secondary' }} badge-status">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                    <td class="small text-muted">{{ $order->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">No orders yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- Right --}}
        <div class="col-lg-4">
            {{-- Actions --}}
            <div class="table-card p-4 mb-4">
                <h6 class="fw-semibold mb-3">Actions</h6>
                <div class="d-grid gap-2">
                    @if ($restaurant->status === 'pending')
                        <form method="POST" action="{{ route('admin.restaurants.approve', $restaurant) }}">
                            @csrf
                            <button class="btn btn-success w-100">
                                <i class="bi bi-check-circle me-2"></i>Approve Restaurant
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.restaurants.edit', $restaurant) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-2"></i>Edit Restaurant
                    </a>

                    <form method="POST" action="{{ route('admin.restaurants.toggle-status', $restaurant) }}">
                        @csrf
                        <button class="btn btn-outline-secondary w-100">
                            <i class="bi bi-toggle-on me-2"></i>
                            Toggle Open/Closed
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.restaurants.destroy', $restaurant) }}"
                        onsubmit="return confirm('Delete this restaurant? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash me-2"></i>Delete Restaurant
                        </button>
                    </form>
                </div>
            </div>

            {{-- Owner Info --}}
            <div class="table-card p-4 mb-4">
                <h6 class="fw-semibold mb-3">Owner Details</h6>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="{{ $restaurant->owner->avatar }}" width="48" height="48"
                        class="rounded-circle object-fit-cover border">
                    <div>
                        <div class="fw-semibold small">{{ $restaurant->owner->name }}</div>
                        <div class="text-muted" style="font-size:12px">{{ $restaurant->owner->email }}</div>
                        <div class="text-muted" style="font-size:12px">{{ $restaurant->owner->phone }}</div>
                    </div>
                </div>
            </div>

            {{-- Restaurant Details --}}
            <div class="table-card p-4">
                <h6 class="fw-semibold mb-3">Details</h6>
                <div class="mb-2 small">
                    <span class="text-muted d-block">Address</span>
                    <span>{{ $restaurant->address }}, {{ $restaurant->city }}, {{ $restaurant->pincode }}</span>
                </div>
                <div class="mb-2 small">
                    <span class="text-muted d-block">Min Order</span>
                    <span>₹{{ number_format($restaurant->minimum_order, 0) }}</span>
                </div>
                <div class="mb-2 small">
                    <span class="text-muted d-block">Delivery Fee</span>
                    <span>₹{{ number_format($restaurant->delivery_fee, 0) }}</span>
                </div>
                <div class="mb-2 small">
                    <span class="text-muted d-block">Delivery Time</span>
                    <span>{{ $restaurant->delivery_time }} minutes</span>
                </div>
                <div class="mb-2 small">
                    <span class="text-muted d-block">Commission</span>
                    <span>{{ $restaurant->commission_percentage }}%</span>
                </div>
                <div class="mb-2 small">
                    <span class="text-muted d-block">Rating</span>
                    <span class="text-warning">
                        <i class="bi bi-star-fill"></i>
                        {{ number_format($restaurant->rating, 1) }}
                        ({{ $restaurant->total_reviews }} reviews)
                    </span>
                </div>
                <div class="small">
                    <span class="text-muted d-block">Joined</span>
                    <span>{{ $restaurant->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    @endsection
