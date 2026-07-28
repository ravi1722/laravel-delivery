@extends('layouts.app')

@section('title', 'Menu Items')
@section('page-title', 'Menu Items')

@section('content')
    {{-- Filter Bar --}}
    <div class="table-card mb-4">
        <div class="p-3">
            <form method="GET" action="{{ route('restaurant.menu-items.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search items..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select form-select-sm">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="food_type" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        <option value="veg" {{ request('food_type') === 'veg' ? 'selected' : '' }}>Veg</option>
                        <option value="non_veg" {{ request('food_type') === 'non_veg' ? 'selected' : '' }}>Non-Veg</option>
                        <option value="egg" {{ request('food_type') === 'egg' ? 'selected' : '' }}>Egg</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                        <i class="bi bi-search"></i>
                    </button>
                    <a href="{{ route('restaurant.menu-items.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Items Grid --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-semibold mb-0">
            Menu Items
            <span class="badge bg-secondary ms-2">{{ $items->total() }}</span>
        </h6>
        <a href="{{ route('restaurant.menu-items.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Item
        </a>
    </div>

    <div class="row g-3">
        @forelse ($items as $item)
            <div class="col-xl-4 col-md-6">
                <div class="table-card h-100">
                    <div class="position-relative">
                        @if ($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" class="w-100 object-fit-cover rounded-top"
                                style="height:160px">
                        @else
                            <div class="w-100 rounded-top d-flex align-items-center justify-content-center"
                                style="height:160px;background:#f8f9fa;color:#dee2e6">
                                <i class="bi bi-egg-fried" style="font-size:48px"></i>
                            </div>
                        @endif

                        {{-- Food Type Badge --}}
                        <span class="position-absolute top-0 start-0 m-2">
                            @if ($item->food_type === 'veg')
                                <span class="badge" style="background:#28a745;font-size:10px">🟢 Veg</span>
                            @elseif($item->food_type === 'non_veg')
                                <span class="badge" style="background:#dc3545;font-size:10px">🔴 Non-Veg</span>
                            @else
                                <span class="badge" style="background:#ffc107;color:#000;font-size:10px">🟡 Egg</span>
                            @endif
                        </span>

                        {{-- Available Toggle --}}
                        <div class="position-absolute top-0 end-0 m-2">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input toggle-availability" type="checkbox"
                                    data-id="{{ $item->id }}" {{ $item->is_available ? 'checked' : '' }}
                                    style="width:36px;height:18px;cursor:pointer">
                            </div>
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="fw-semibold mb-0 small">{{ $item->name }}</h6>
                            <div class="text-end">
                                @if ($item->discount_price)
                                    <div class="fw-bold small" style="color:#FF6B35">
                                        ₹{{ number_format($item->discount_price, 0) }}
                                    </div>
                                    <div class="text-muted text-decoration-line-through" style="font-size:11px">
                                        ₹{{ number_format($item->price, 0) }}
                                    </div>
                                @else
                                    <div class="fw-bold small" style="color:#FF6B35">
                                        ₹{{ number_format($item->price, 0) }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="text-muted mb-2" style="font-size:11px">
                            {{ $item->category->name }}
                            @if ($item->preparation_time)
                                · ⏱ {{ $item->preparation_time }} min
                            @endif
                        </div>

                        @if ($item->description)
                            <p class="text-muted mb-2" style="font-size:12px;line-height:1.4">
                                {{ Str::limit($item->description, 60) }}
                            </p>
                        @endif

                        <div class="d-flex gap-2 mt-2">
                            <a href="{{ route('restaurant.menu-items.edit', $item) }}"
                                class="btn btn-outline-primary btn-sm flex-fill" style="font-size:12px">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('restaurant.menu-items.destroy', $item) }}"
                                onsubmit="return confirm('Delete this item?')" class="flex-fill">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm w-100" style="font-size:12px">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="table-card p-5 text-center text-muted">
                    <i class="bi bi-egg-fried fs-1 d-block mb-3 opacity-25"></i>
                    No menu items found.
                    <a href="{{ route('restaurant.menu-items.create') }}" class="d-block mt-2">
                        Add your first item
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($items->hasPages())
        <div class="mt-4">
            {{ $items->withQueryString()->links() }}
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        // Toggle availability via AJAX
        $('.toggle-availability').on('change', function() {
            const id = $(this).data('id');
            const checkbox = $(this);

            $.post(`/restaurant/menu-items/${id}/toggle`, {}, function(response) {
                if (response.success) {
                    // Show toast
                    const toast = `<div class="position-fixed bottom-0 end-0 p-3" style="z-index:9999">
                        <div class="toast show align-items-center text-white bg-success border-0">
                            <div class="d-flex">
                                <div class="toast-body small">${response.message}</div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto"
                                        onclick="this.closest('.position-fixed').remove()"></button>
                            </div>
                        </div>
                    </div>`;
                    $('body').append(toast);
                    setTimeout(() => $('.position-fixed').last().remove(), 3000);
                }
            }).fail(function() {
                // Revert toggle on error
                checkbox.prop('checked', !checkbox.prop('checked'));
                alert('Failed to update availability. Please try again.');
            });
        });
    </script>
@endpush
