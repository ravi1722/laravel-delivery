@extends('layouts.app')

@section('title', 'Add Restaurant')
@section('page-title', 'Add New Restaurant')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="table-card p-4">
                <h5 class="fw-semibold mb-4 pb-3 border-bottom">
                    <i class="bi bi-shop me-2" style="color:#FF6B35"></i>
                    Add New Restaurant
                </h5>

                <form method="POST" action="{{ route('admin.restaurants.store') }}" enctype="multipart/form-data">
                    @csrf
                    {{-- Owner Selection --}}
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="fw-semibold text-muted small text-uppercase mb-3">
                                Restaurant Owner
                            </h6>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small">
                                Select Owner <span class="text-danger">*</span>
                            </label>
                            <select name="owner_id" class="form-select @error('owner_id') is-invalid @enderror" required>
                                @foreach ($owners as $owner)
                                    <option value="{{ $owner->id }}"
                                        {{ old('owner_id') == $owner->id ? 'selected' : '' }}>
                                        {{ $owner->name }} — {{ $owner->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('owner_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Only users registered as Restaurant Owner are listed.
                            </div>
                        </div>
                    </div>

                    {{-- Basic Info --}}
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="fw-semibold text-muted small text-uppercase mb-3">
                                Basic Information
                            </h6>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-semibold small">
                                Restaurant Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="e.g. Spice Garden" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">
                                Cuisine Type <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="cuisine_type"
                                class="form-control @error('cuisine_type') is-invalid @enderror"
                                value="{{ old('cuisine_type') }}" placeholder="e.g. North Indian" required>
                            @error('cuisine_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small">Description</label>
                            <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror"
                                placeholder="Describe the restaurant...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                City <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                                value="{{ old('city') }}" placeholder="City" required>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">
                                State <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="state" class="form-control @error('state') is-invalid @enderror"
                                value="{{ old('state') }}" placeholder="State" required>
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">
                                Pincode <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="pincode"
                                class="form-control @error('pincode') is-invalid @enderror" value="{{ old('pincode') }}"
                                placeholder="6-digit pincode" required>
                            @error('pincode')
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

                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">
                                Min Order (₹) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="minimum_order"
                                    class="form-control @error('minimum_order') is-invalid @enderror"
                                    value="{{ old('minimum_order', 0) }}" min="0" step="10" required>
                            </div>
                            @error('minimum_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">
                                Delivery Fee (₹) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="delivery_fee"
                                    class="form-control @error('delivery_fee') is-invalid @enderror"
                                    value="{{ old('delivery_fee', 0) }}" min="0" step="5" required>
                            </div>
                            @error('delivery_fee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">
                                Delivery Time (min) <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="delivery_time"
                                class="form-control @error('delivery_time') is-invalid @enderror"
                                value="{{ old('delivery_time', 30) }}" min="5" max="120" required>
                            @error('delivery_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">
                                Commission (%) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" name="commission_percentage"
                                    class="form-control @error('commission_percentage') is-invalid @enderror"
                                    value="{{ old('commission_percentage', 10) }}" min="0" max="100"
                                    step="0.5" required>
                                <span class="input-group-text">%</span>
                            </div>
                            @error('commission_percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Admin Settings --}}
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="fw-semibold text-muted small text-uppercase mb-3">
                                Admin Settings
                            </h6>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Status</label>
                            <select name="status" class="form-select">
                                <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>
                                    Pending Review
                                </option>
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                        </div>

                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                                    id="isFeatured" {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold small" for="isFeatured">
                                    ⭐ Mark as Featured
                                </label>
                            </div>
                        </div>

                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_open" value="1"
                                    id="isOpen" {{ old('is_open') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold small" for="isOpen">
                                    🟢 Mark as Open
                                </label>
                            </div>
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
                            <label class="form-label fw-semibold small">Restaurant Logo</label>
                            <input type="file" name="logo"
                                class="form-control @error('logo') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/webp" id="logoInput">
                            <div class="form-text">JPG, PNG, WEBP — max 2MB</div>
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <img id="logoPreview" src="" class="mt-2 rounded-circle d-none" width="80"
                                height="80" style="object-fit:cover;border:2px solid #dee2e6">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Cover Image</label>
                            <input type="file" name="cover_image"
                                class="form-control @error('cover_image') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/webp" id="coverInput">
                            <div class="form-text">JPG, PNG, WEBP — max 4MB</div>
                            @error('cover_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <img id="coverPreview" src="" class="mt-2 rounded-3 d-none w-100"
                                style="height:100px;object-fit:cover">
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="d-flex gap-3 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-check-circle me-2"></i>
                            Create Restaurant
                        </button>
                        <a href="{{ route('admin.restaurants.index') }}" class="btn btn-outline-secondary">
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
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    const preview = document.getElementById('logoPreview');
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });

        // Cover preview
        document.getElementById('coverInput').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    const preview = document.getElementById('coverPreview');
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
