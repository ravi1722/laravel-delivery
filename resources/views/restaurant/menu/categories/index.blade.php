@extends('layouts.app')

@section('title', 'Menu Categories')
@section('page-title', 'Menu Categories')

@section('content')
    <div class="row g-4">
        {{-- Add Category Form --}}
        <div class="col-lg-4">
            <div class="table-card p-4">
                <h6 class="fw-semibold mb-3">Add New Category</h6>
                <form method="POST" action="{{ route('restaurant.menu-categories.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Category Name *</label>
                        <input type="text" name="name"
                            class="form-control form-control-sm @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="e.g. Starters, Main Course" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Description</label>
                        <textarea name="description" rows="2" class="form-control form-control-sm" placeholder="Optional description">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Category Image</label>
                        <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control form-control-sm"
                            value="{{ old('sort_order', 0) }}" min="0">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-plus-lg me-1"></i> Add Category
                    </button>
                </form>
            </div>
        </div>

        {{-- Categories List --}}
        <div class="col-lg-8">
            <div class="table-card">
                <div class="p-4 border-bottom">
                    <h6 class="fw-semibold mb-0">
                        All Categories
                        <span class="badge bg-secondary ms-2">{{ $categories->count() }}</span>
                    </h6>
                </div>
                @forelse($categories as $category)
                    <div class="p-3 border-bottom d-flex align-items-center gap-3">
                        @if ($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" width="50" height="50"
                                class="rounded-3 object-fit-cover">
                        @else
                            <div class="rounded-3 d-flex align-items-center justify-content-center"
                                style="width:50px;height:50px;background:#f8f9fa;color:#adb5bd">
                                <i class="bi bi-image fs-5"></i>
                            </div>
                        @endif
                        <div class="flex-fill">
                            <div class="fw-semibold small">{{ $category->name }}</div>
                            <div class="text-muted" style="font-size:11px">
                                {{ $category->items_count }} items
                                @if ($category->description)
                                    · {{ Str::limit($category->description, 50) }}
                                @endif
                            </div>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }} badge-status">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>

                            <button class="btn btn-xs btn-outline-secondary" data-bs-toggle="modal"
                                data-bs-target="#editCategory{{ $category->id }}" style="font-size:11px;padding:3px 7px">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <form method="POST" action="{{ route('restaurant.menu-categories.destroy', $category) }}"
                                onsubmit="return confirm('Delete this category?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-xs btn-outline-danger" style="font-size:11px;padding:3px 7px">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    {{-- Edit Modal --}}
                    <div class="modal fade" id="editCategory{{ $category->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="modal-title fw-semibold">Edit Category</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('restaurant.menu-categories.update', $category) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">Name *</label>
                                            <input type="text" name="name" class="form-control form-control-sm"
                                                value="{{ $category->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">Description</label>
                                            <textarea name="description" rows="2" class="form-control form-control-sm">{{ $category->description }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">Image</label>
                                            <input type="file" name="image" class="form-control form-control-sm"
                                                accept="image/*">
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_active"
                                                    value="1" {{ $category->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label small">Active</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-list-ul fs-2 d-block mb-2 opacity-25"></i>
                        No categories yet. Add your first category!
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
