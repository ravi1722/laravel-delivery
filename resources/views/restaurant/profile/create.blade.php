@extends('layouts.app')

@section('title', 'Setup Restaurant Profile')
@section('page-title', 'Setup Your Restaurant')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Info Banner --}}
            <div class="alert alert-info d-flex align-items-center gap-3 mb-4">
                <i class="bi bi-info-circle-fill fs-4"></i>
                <div>
                    <strong>Welcome to QuickBite!</strong>
                    Fill in your restaurant details below. Your restaurant will be
                    reviewed and activated by our admin team within 24 hours.
                </div>
            </div>

            <div class="table-card p-4">
                <h5 class="fw-semibold mb-4 pb-3 border-bottom">
                    <i class="bi bi-shop me-2" style="color:#FF6B35"></i>
                    Restaurant Information
                </h5>

                <form method="POST" action="{{ route('restaurant.profile.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Basic Info --}}
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="fw-semibold text-muted small text-uppercase mb-3">
                                Basic Information
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">
                                Restaurant Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="e.g. Lakshmi Biriyani" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">
                                Cuisine Type <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="cuisine_type"
                                class="form-control @error('cuisine_type') is-invalid @enderror"
                                value="{{ old('cuisine_type') }}" placeholder="e.g. North Indian, Chinese, Pizza" required>
                            @error('cuisine_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small">Description</label>
                            <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror"
                                placeholder="Describe your restaurant...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Contact Info --}}
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="fw-semibold text-muted small text-uppercase mb-3">
                                Contact Details
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">
                                Phone Number <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone') }}" placeholder="10-digit mobile number" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="restaurant@example.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                            <label class="form-label fw-semibold small">
                                Full Address <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                                value="{{ old('address') }}" placeholder="Street address, building, landmark" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">
                                Pincode <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="pincode" class="form-control @error('pincode') is-invalid @enderror"
                                value="{{ old('pincode') }}" id="pincode" placeholder="6-digit pincode" required>
                            @error('pincode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">
                                City <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                                value="{{ old('city') }}" id="city" placeholder="City - auto filled from pincode"
                                readonly>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">
                                State <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="state" class="form-control @error('state') is-invalid @enderror"
                                value="{{ old('state') }}" id="state" placeholder="State  - auto filled from pincode"
                                readonly>
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                            <label class="form-label fw-semibold small">
                                Minimum Order (₹) <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="minimum_order"
                                class="form-control @error('minimum_order') is-invalid @enderror"
                                value="{{ old('minimum_order', 0) }}" min="0" step="10" required>
                            @error('minimum_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">
                                Delivery Fee (₹) <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="delivery_fee"
                                class="form-control @error('delivery_fee') is-invalid @enderror"
                                value="{{ old('delivery_fee', 0) }}" min="0" step="5" required>
                            @error('delivery_fee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">
                                Avg Delivery Time (minutes) <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="delivery_time"
                                class="form-control @error('delivery_time') is-invalid @enderror"
                                value="{{ old('delivery_time', 30) }}" min="5" max="120" required>
                            @error('delivery_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                            <label class="form-label fw-semibold small">
                                Restaurant Logo
                            </label>
                            <input type="file" name="logo"
                                class="form-control @error('logo') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/webp" id="logoInput">
                            <div class="form-text">JPG, PNG, WEBP — max 2MB</div>
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            {{-- Preview --}}
                            <img id="logoPreview" src="" class="mt-2 rounded-circle d-none" width="80"
                                height="80" style="object-fit:cover;border:2px solid #dee2e6">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">
                                Cover Image
                            </label>
                            <input type="file" name="cover_image"
                                class="form-control @error('cover_image') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/webp" id="coverInput">
                            <div class="form-text">JPG, PNG, WEBP — max 4MB, recommended 1200x400</div>
                            @error('cover_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            {{-- Preview --}}
                            <img id="coverPreview" src="" class="mt-2 rounded-3 d-none w-100"
                                style="height:100px;object-fit:cover">
                        </div>
                    </div>
                    {{-- Submit --}}
                    <div class="d-flex gap-3 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-check-circle me-2"></i>
                            Submit for Review
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

@push('scripts')
    <script>
        // Logo preview
        document.getElementById('logoInput').addEventListener('change', function() {
            const files = this.files[0];

            if (files) {
                let reader = new FileReader();
                reader.onload = e => {
                    const preview = document.getElementById('logoPreview');
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                reader.readAsDataURL(files);
            }
        });

        // cover preview
        document.getElementById('coverInput').addEventListener('change', function() {
            const files = this.files[0];

            if (files) {
                let reader = new FileReader();
                reader.onload = e => {
                    const preview = document.getElementById('coverPreview');
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                reader.readAsDataURL(files);
            }
        });

        // get city state
        document.getElementById('pincode').addEventListener('keyup', function(e) {
            let pincode = this.value;

            if (pincode.length == 6) {
                $.ajax({
                    url: '/restaurant/profile/get-city-state/' + pincode,
                    type: 'GET',
                    success: function(response) {
                        // $('.invalid-feedback').remove();
                        // $('.is-invalid').removeClass('is-invalid');
                        if (response.success) {
                            document.getElementById("city").value = response.data.city;
                            document.getElementById("state").value = response.data.state;
                        } else {
                            // $('#pincode').addClass('is-invalid');
                            // $('#pincode').after('<div class="invalid-feedback">' + response.message + '</div>');
                            // $('#city').val('');
                            // $('#state').val('');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseJSON.message);
                    }
                });
            }
        });
    </script>
@endpush
