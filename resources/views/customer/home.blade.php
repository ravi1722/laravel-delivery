@extends('layouts.app')

@section('title', 'Order Food Online')
@section('page-title', 'Browse Restaurants')

@section('content')
    {{-- Search Bar --}}
    <div class="table-card p-4 mb-4">
        <form method="GET" action="" class="row g-3 align-items-end">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0"
                        placeholder="Search restaurants, cuisines..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="city" class="form-select">
                    <option value="">All Cities</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>
                            {{ $city }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i> Search
                </button>
            </div>
        </form>
    </div>

    {{-- Featured Restaurants --}}
    @if ($featuredRestaurants->count() > 0)
        <div class="mb-4">
            <h5 class="fw-semibold mb-3">
                <i class="bi bi-star-fill text-warning me-2"></i>Featured Restaurants
            </h5>
            <div class="row g-3">
                @foreach ($featuredRestaurants as $restaurant)
                    <div class="col-xl-3 col-md-6">
                        @include('partials.restaurant-card', [
                            'restaurant' => $restaurant,
                            'featured' => true,
                        ])
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- All Restaurants --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-semibold mb-0">
            All Restaurants
            <span class="badge bg-secondary ms-2">{{ $restaurants->count() }}</span>
        </h5>
    </div>

    <div class="row g-3">
        @forelse($restaurants as $restaurant)
            <div class="col-xl-3 col-md-6">
                @include('partials.restaurant-card', ['restaurant' => $restaurant])
            </div>
        @empty
            <div class="col-12">
                <div class="table-card p-5 text-center text-muted">
                    <i class="bi bi-shop fs-1 d-block mb-3 opacity-25"></i>
                    No restaurants found matching your search.
                </div>
            </div>
        @endforelse
    </div>

    @if ($restaurants->hasPages())
        <div class="mt-4">
            {{ $restaurants->withQueryString()->links() }}
        </div>
    @endif
@endsection
