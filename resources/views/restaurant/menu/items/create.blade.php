@extends('layouts.app')

@section('title', 'Add Menu Item')
@section('page-title', 'Add Menu Item')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="table-card p-4">
                <h5 class="fw-semibold mb-4 pb-3 border-bottom">
                    <i class="bi bi-plus-circle me-2" style="color:#FF6B35"></i>
                    Add New Menu Item
                </h5>

                <form method="POST" action="{{ route('restaurant.menu-items.store') }}" enctype="multipart/form-data"
                    id="menuItemForm">
                    @csrf
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
                                value="{{ old('name') }}" placeholder="e.g. Butter Chicken, Margherita Pizza" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">
                                Category <span class="text-danger">*</span>
                            </label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror"
                                required>
                                <option value="">Select category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small">Description</label>
                            <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror"
                                placeholder="Describe the dish, ingredients, taste...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                            <label class="form-label fw-semibold small">
                                Price (₹) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="price"
                                    class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}"
                                    min="0" step="0.01" placeholder="0.00" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">
                                Discount Price (₹)
                                <small class="text-muted">(optional)</small>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="discount_price"
                                    class="form-control @error('discount_price') is-invalid @enderror"
                                    value="{{ old('discount_price') }}" min="0" step="0.01" placeholder="0.00">
                            </div>
                            @error('discount_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">
                                Food Type <span class="text-danger">*</span>
                            </label>
                            <select name="food_type" class="form-select @error('food_type') is-invalid @enderror" required>
                                <option value="veg" {{ old('food_type') === 'veg' ? 'selected' : '' }}>
                                    🟢 Vegetarian
                                </option>
                                <option value="non_veg" {{ old('food_type') === 'non_veg' ? 'selected' : '' }}>
                                    🔴 Non-Vegetarian
                                </option>
                                <option value="egg" {{ old('food_type') === 'egg' ? 'selected' : '' }}>
                                    🟡 Egg
                                </option>
                            </select>
                            @error('food_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Additional Info --}}
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="fw-semibold text-muted small text-uppercase mb-3">
                                Additional Details
                            </h6>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">
                                Preparation Time (minutes)
                            </label>
                            <input type="number" name="preparation_time"
                                class="form-control @error('preparation_time') is-invalid @enderror"
                                value="{{ old('preparation_time', 15) }}" min="1" max="120">
                            @error('preparation_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">
                                Calories <small class="text-muted">(optional)</small>
                            </label>
                            <input type="number" name="calories"
                                class="form-control @error('calories') is-invalid @enderror" value="{{ old('calories') }}"
                                min="0" placeholder="e.g. 350">
                            @error('calories')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control"
                                value="{{ old('sort_order', 0) }}" min="0">
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_available" value="1"
                                    id="isAvailable" {{ old('is_available', true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold small" for="isAvailable">
                                    Available for Order
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                                    id="isFeatured" {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold small" for="isFeatured">
                                    ⭐ Mark as Popular / Featured
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Item Image --}}
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="fw-semibold text-muted small text-uppercase mb-3">
                                Item Image
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <input type="file" name="image"
                                class="form-control @error('image') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/webp" id="itemImageInput">
                            <div class="form-text">JPG, PNG, WEBP — max 2MB</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <img id="itemImagePreview" src="" class="mt-3 rounded-3 d-none"
                                style="width:160px;height:120px;object-fit:cover;border:2px solid #dee2e6">
                        </div>
                    </div>

                    {{-- Variants --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="fw-semibold text-muted small text-uppercase mb-0">
                                Variants
                                <small class="text-muted fw-normal text-lowercase">
                                    (e.g. Small, Medium, Large)
                                </small>
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addVariant">
                                <i class="bi bi-plus-lg me-1"></i>Add Variant
                            </button>
                        </div>

                        <div id="variantsContainer">
                            {{-- Variants added dynamically --}}
                        </div>
                    </div>

                    {{-- Addons --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="fw-semibold text-muted small text-uppercase mb-0">
                                Add-ons / Extras
                                <small class="text-muted fw-normal text-lowercase">
                                    (e.g. Extra Cheese, Extra Sauce)
                                </small>
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addAddon">
                                <i class="bi bi-plus-lg me-1"></i>Add Extra
                            </button>
                        </div>

                        <div id="addonsContainer">
                            {{-- Addons added dynamically --}}
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="d-flex gap-3 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-check-circle me-2"></i>Add Item
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

@push('scripts')
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

        // Variant rows
        let variantCount = 0;
        document.getElementById('addVariant').addEventListener('click', function() {
            const container = document.getElementById('variantsContainer');
            const row = document.createElement('div');
            row.className = "row g-2 mb-2 align-items-center variant-row";

            row.innerHTML = `
                    <div class="col-md-6">
                        <input type="text"
                            name="variants[${variantCount}][name]"
                            class="form-control form-control-sm"
                            placeholder="Variant name (e.g. Large)"
                            required>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">₹</span>
                            <input type="number"
                                name="variants[${variantCount}][price]"
                                class="form-control"
                                placeholder="Price"
                                min="0" step="0.01" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button"
                                class="btn btn-sm btn-outline-danger w-100 remove-row">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
            container.appendChild(row);
            variantCount++;
        });

        let addonCount = 0;
        document.getElementById('addAddon').addEventListener('click', function() {
            const container = document.getElementById('addonsContainer');
            const row = document.createElement('div');
            row.className = 'row g-2 mb-2 align-items-center addon-row';
            row.innerHTML = `
                    <div class="col-md-6">
                        <input type="text"
                            name="addons[${addonCount}][name]"
                            class="form-control form-control-sm"
                            placeholder="Extra name (e.g. Extra Cheese)"
                            required>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">₹</span>
                            <input type="number"
                                name="addons[${addonCount}][price]"
                                class="form-control"
                                placeholder="Price (0 = free)"
                                min="0" step="0.01" value="0">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button"
                                class="btn btn-sm btn-outline-danger w-100 remove-row" >
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
            container.appendChild(row);
            addonCount++;
        });

        $(document).on('click', '.remove-row', function() {
            $(this).closest('.row').remove();
        });
    </script>
@endpush
