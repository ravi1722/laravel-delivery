@extends('layouts.app')

@section('title', 'Edit Menu Item')
@section('page-title', 'Edit Menu Item')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="table-card p-4">
                <h5 class="fw-semibold mb-4 pb-3 border-bottom">
                    <i class="bi bi-pencil me-2" style="color:#FF6B35"></i>
                    Edit: {{ $menuItem->name }}
                </h5>
                <form method="POST" action="{{ route('restaurant.menu-items.update', $menuItem) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    {{-- Basic Info --}}
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="fw-semibold text-muted small text-uppercase mb-3">
                                Basic Information
                            </h6>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-semibold small">
                                Item Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $menuItem->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">
                                Category <span class="text-danger">*</span>
                            </label>
                            <select name="category_id" class="form-select" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $menuItem->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small">Description</label>
                            <textarea name="description" rows="3" class="form-control">{{ old('description', $menuItem->description) }}</textarea>
                        </div>
                    </div>

                    {{-- Pricing --}}
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="fw-semibold text-muted small text-uppercase mb-3">
                                Pricing
                            </h6>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Price (₹)</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="price"
                                    class="form-control @error('price') is-invalid @enderror"
                                    value="{{ old('price', $menuItem->price) }}" min="0" step="0.01" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">
                                Discount Price (₹)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="discount_price" class="form-control"
                                    value="{{ old('discount_price', $menuItem->discount_price) }}" min="0"
                                    step="0.01">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Food Type</label>
                            <select name="food_type" class="form-select" required>
                                <option value="veg"
                                    {{ old('food_type', $menuItem->food_type) === 'veg' ? 'selected' : '' }}>
                                    🟢 Vegetarian
                                </option>
                                <option value="non_veg"
                                    {{ old('food_type', $menuItem->food_type) === 'non_veg' ? 'selected' : '' }}>
                                    🔴 Non-Vegetarian
                                </option>
                                <option value="egg"
                                    {{ old('food_type', $menuItem->food_type) === 'egg' ? 'selected' : '' }}>
                                    🟡 Egg
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- Additional --}}
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="fw-semibold text-muted small text-uppercase mb-3">
                                Additional Details
                            </h6>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">
                                Preparation Time (min)
                            </label>
                            <input type="number" name="preparation_time" class="form-control"
                                value="{{ old('preparation_time', $menuItem->preparation_time) }}" min="1"
                                max="120">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Calories</label>
                            <input type="number" name="calories" class="form-control"
                                value="{{ old('calories', $menuItem->calories) }}" min="0">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control"
                                value="{{ old('sort_order', $menuItem->sort_order) }}" min="0">
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_available" value="1"
                                    {{ old('is_available', $menuItem->is_available) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold small">
                                    Available for Order
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                                    {{ old('is_featured', $menuItem->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold small">
                                    ⭐ Mark as Popular / Featured
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Image --}}
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="fw-semibold text-muted small text-uppercase mb-3">
                                Item Image
                            </h6>
                        </div>
                        <div class="col-md-6">
                            @if ($menuItem->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $menuItem->image) }}"
                                        class="rounded-3 object-fit-cover" style="width:160px;height:120px">
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control" accept="image/*" id="itemImageInput">
                            <div class="form-text">Leave empty to keep current image</div>
                        </div>
                    </div>

                    {{-- Existing Variants --}}
                    @if ($menuItem->variants->count() > 0)
                        <div class="mb-4">
                            <h6 class="fw-semibold text-muted small text-uppercase mb-3">
                                Current Variants
                            </h6>
                            <div class="table-card">
                                @foreach ($menuItem->variants as $variant)
                                    <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                                        <div>
                                            <span class="fw-semibold small">{{ $variant->name }}</span>
                                            <span class="text-muted small ms-3">
                                                ₹{{ number_format($variant->price, 0) }}
                                            </span>
                                        </div>
                                        <span
                                            class="badge {{ $variant->is_available ? 'bg-success' : 'bg-secondary' }} badge-status">
                                            {{ $variant->is_available ? 'Available' : 'Unavailable' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-text mt-1">
                                Variant editing coming in next update. Delete and re-add to change.
                            </div>
                        </div>
                    @endif

                    {{-- Existing Addons --}}
                    @if ($menuItem->addons->count() > 0)
                        <div class="mb-4">
                            <h6 class="fw-semibold text-muted small text-uppercase mb-3">
                                Current Add-ons
                            </h6>
                            <div class="table-card">
                                @foreach ($menuItem->addons as $addon)
                                    <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                                        <span class="fw-semibold small">{{ $addon->name }}</span>
                                        <span class="small" style="color:#FF6B35">
                                            @if ($addon->price > 0)
                                                +₹{{ number_format($addon->price, 0) }}
                                            @else
                                                Free
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Submit --}}
                    <div class="d-flex gap-3 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-check-circle me-2"></i>Save Changes
                        </button>
                        <a href="{{ route('restaurant.menu-items.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

{{-- @push('scripts')
    <script>
        document.getElementById('itemImageInput').addEventListener('change', function() {
            const file = this.files[0];

            if (file) {
                const fileReader = new FileReader();
                fileReader.onload = e => {
                    const preview = document.getElementById('itemImagePreview');
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                fileReader.readAsDataURL(file);
            }
        });
    </script>
@endpush --}}
