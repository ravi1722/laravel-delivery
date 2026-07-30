<a href="{{ route('customer.restaurant', $restaurant) }}" class="text-decoration-none">
    <div class="table-card h-100 overflow-hidden restaurant-card">
        {{-- Cover Image --}}
        <div class="position-relative">
            @if ($restaurant->cover_image)
                <img src="{{ asset('storage/' . $restaurant->cover_image) }}" class="w-100 object-fit-cover"
                    style="height:140px">
            @else
                <div class="w-100 d-flex align-items-center justify-content-center"
                    style="height:140px;background:linear-gradient(135deg,#FF6B35,#e55a25)">
                    <i class="bi bi-shop text-white" style="font-size:48px;opacity:.5"></i>
                </div>
            @endif

            {{-- Logo --}}
            <div class="position-absolute" style="bottom:-20px;left:16px">
                <img src="{{ $restaurant->logo }}" width="48" height="48"
                    class="rounded-circle border-3 border-white border object-fit-cover"
                    style="border:3px solid #fff!important">
            </div>

            {{-- Featured Badge --}}
            @if (isset($featured) && $featured)
                <span class="position-absolute top-0 end-0 m-2 badge" style="background:#FF6B35;font-size:10px">
                    <i class="bi bi-star-fill"></i> Featured
                </span>
            @endif

            {{-- Open/Closed Badge --}}
            <span
                class="position-absolute bottom-0 end-0 m-2 badge
                          {{ $restaurant->is_open ? 'bg-success' : 'bg-danger' }}"
                style="font-size:10px">
                {{ $restaurant->is_open ? 'Open' : 'Closed' }}
            </span>
        </div>

        {{-- Content --}}
        <div class="p-3 pt-5">
            <h6 class="fw-semibold mb-1 text-dark">{{ $restaurant->name }}</h6>
            <div class="text-muted mb-2" style="font-size:12px">
                {{ $restaurant->cuisine_type }}
                @if ($restaurant->city)
                    · {{ $restaurant->city }}
                @endif
            </div>
            <div class="d-flex align-items-center justify-content-between" style="font-size:12px">
                <span class="text-warning fw-semibold">
                    <i class="bi bi-star-fill"></i>
                    {{ number_format($restaurant->rating, 1) }}
                    <span class="text-muted fw-normal">({{ $restaurant->total_reviews }})</span>
                </span>
                <span class="text-muted">
                    <i class="bi bi-clock me-1"></i>{{ $restaurant->delivery_time }} min
                </span>
                <span class="text-muted">
                    @if ($restaurant->delivery_fee > 0)
                        ₹{{ number_format($restaurant->delivery_fee, 0) }} delivery
                    @else
                        <span class="text-success">Free delivery</span>
                    @endif
                </span>
            </div>
            @if ($restaurant->minimum_order > 0)
                <div class="text-muted mt-1" style="font-size:11px">
                    Min order: ₹{{ number_format($restaurant->minimum_order, 0) }}
                </div>
            @endif
        </div>
    </div>
</a>

@push('styles')
    <style>
        .restaurant-card {
            transition: all .2s;
        }

        .restaurant-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
        }
    </style>
@endpush
