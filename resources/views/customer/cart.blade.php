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

        </div>
    @endif
@endsection
