@extends('layouts.app')

@section('title', 'Edit Restaurant Profile')
@section('page-title', 'Edit Restaurant Profile')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="table-card p-4">
                <h5 class="fw-semibold mb-4 pb-3 border-bottom">
                    <i class="bi bi-pencil me-2" style="color:#FF6B35"></i>
                    Update Restaurant Details
                </h5>

                <form method="POST" action="{{ route('restaurant.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    {{-- Basic Info --}}
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="fw-semibold text-muted small text-uppercase mb-3">
                                Basic Information
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Restaurant Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $restaurant->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Cuisine Type</label>
                            <input type="text" name="cuisine_type"
                                class="form-control @error('cuisine_type') is-invalid @enderror"
                                value="{{ old('cuisine_type', $restaurant->cuisine_type) }}" required>
                            @error('cuisine_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small">Description</label>
                            <textarea name="description" rows="3" class="form-control">{{ old('description', $restaurant->description) }}</textarea>
                        </div>
                    </div>

                    {{-- Contact --}}
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="fw-semibold text-muted small text-uppercase mb-3">
                                Contact Details
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Phone</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone', $restaurant->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $restaurant->email) }}">
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="fw-semibold text-muted small text-uppercase mb-3">
                                Location
                            </h6>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Full Address</label>
                            <input type="text" name="address" class="form-control"
                                value="{{ old('address', $restaurant->address) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">City</label>
                            <input type="text" name="city" class="form-control"
                                value="{{ old('city', $restaurant->city) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">State</label>
                            <input type="text" name="state" class="form-control"
                                value="{{ old('state', $restaurant->state) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Pincode</label>
                            <input type="text" name="pincode" class="form-control"
                                value="{{ old('pincode', $restaurant->pincode) }}" required>
                        </div>
                    </div>

                    {{-- Delivery Settings --}}
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="fw-semibold text-muted small text-uppercase mb-3">
                                Delivery Settings
                            </h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Min Order (₹)</label>
                            <input type="number" name="minimum_order" class="form-control"
                                value="{{ old('minimum_order', $restaurant->minimum_order) }}" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Delivery Fee (₹)</label>
                            <input type="number" name="delivery_fee" class="form-control"
                                value="{{ old('delivery_fee', $restaurant->delivery_fee) }}" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Delivery Time (min)</label>
                            <input type="number" name="delivery_time" class="form-control"
                                value="{{ old('delivery_time', $restaurant->delivery_time) }}" min="5"
                                max="120" required>
                        </div>
                    </div>

                    {{-- Images --}}
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="fw-semibold text-muted small text-uppercase mb-3">
                                Images
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Logo</label>
                            @if ($restaurant->logo)
                                <div class="mb-2">
                                    <img src="{{ $restaurant->logo }}" width="64" height="64"
                                        class="rounded-circle object-fit-cover border">
                                </div>
                            @endif
                            <input type="file" name="logo" class="form-control" accept="image/*">
                            <div class="form-text">Leave empty to keep current</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Cover Image</label>
                            @if ($restaurant->cover_image)
                                <div class="mb-2">
                                    <img src="{{ $restaurant->cover_image }}"
                                        class="rounded-3 w-100 object-fit-cover" style="height:80px">
                                </div>
                            @endif
                            <input type="file" name="cover_image" class="form-control" accept="image/*">
                            <div class="form-text">Leave empty to keep current</div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="d-flex gap-3 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-check-circle me-2"></i>
                            Save Changes
                        </button>
                        <a href="{{ route('restaurant.dashboard') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
