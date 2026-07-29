@extends('layouts.app')

@section('title', 'Manage Restaurants')
@section('page-title', 'Manage Restaurants')

@section('content')
    {{-- Filters --}}
    <div class="table-card mb-4">
        <div class="p-3">
            <form method="GET" action="{{ route('admin.restaurants.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Search by name, city, cuisine..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="city" class="form-control form-control-sm" placeholder="Filter by city"
                        value="{{ request('city') }}">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.restaurants.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-card">
        <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-semibold mb-0">
                All Restaurants
                <span class="badge bg-secondary ms-2">{{ $restaurants->total() }}</span>
            </h6>
            <a href="{{ route('admin.restaurants.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Add Restaurant
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Restaurant</th>
                        <th>Owner</th>
                        <th>City</th>
                        <th>Orders</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Open</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($restaurants as $restaurant)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $restaurant->logo }}" width="40" height="40"
                                        class="rounded-circle object-fit-cover border">
                                    <div>
                                        <div class="fw-semibold small">{{ $restaurant->name }}</div>
                                        <div class="text-muted" style="font-size:11px">
                                            {{ $restaurant->cuisine_type }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small">{{ $restaurant->owner->name }}</div>
                                <div class="text-muted" style="font-size:11px">{{ $restaurant->owner->phone }}</div>
                            </td>
                            <td class="small">{{ $restaurant->city }}</td>
                            <td class="small text-center">{{ $restaurant->orders_count }}</td>
                            <td>
                                <span class="text-warning small">
                                    <i class="bi bi-star-fill"></i>
                                    {{ number_format($restaurant->rating, 1) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    // $colors = [
                                    //     'pending' => 'warning',
                                    //     'active' => 'success',
                                    //     'inactive' => 'secondary',
                                    //     'suspended' => 'danger',
                                    // ];
                                @endphp
                                <span class="badge bg-{{ $colors[$restaurant->status] }} badge-status">
                                    {{ ucfirst($restaurant->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $restaurant->is_open ? 'bg-success' : 'bg-danger' }} badge-status">
                                    {{ $restaurant->is_open ? 'Open' : 'Closed' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @if ($restaurant->status === 'pending')
                                        <form method="POST"
                                            action="{{ route('admin.restaurants.approve', $restaurant) }}">
                                            @csrf
                                            <button class="btn btn-xs btn-success" title="Approve"
                                                style="font-size:11px;padding:3px 7px">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('admin.restaurants.show', $restaurant) }}"
                                        class="btn btn-xs btn-outline-primary" style="font-size:11px;padding:3px 7px"
                                        title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.restaurants.edit', $restaurant) }}"
                                        class="btn btn-xs btn-outline-secondary" style="font-size:11px;padding:3px 7px"
                                        title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form method="POST" action="{{ route('admin.restaurants.destroy', $restaurant) }}"
                                        onsubmit="return confirm('Delete this restaurant?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-xs btn-outline-danger" style="font-size:11px;padding:3px 7px"
                                            title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-shop fs-2 d-block mb-2 opacity-25"></i>
                                No restaurants found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Pagination --}}
        @if ($restaurants->hasPages())
            <div class="p-4 border-top">
                {{ $restaurants->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
