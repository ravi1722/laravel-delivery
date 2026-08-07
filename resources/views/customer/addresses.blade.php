@extends('layouts.app')

@section('title', 'My Addresses')
@section('page-title', 'My Addresses')

@section('content')
    <div class="row g-4">
        {{-- Add Address Form --}}
        <div class="col-lg-4">
            <div class="table-card p-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-plus-circle me-2" style="color:#FF6B35"></i>
                    Add New Address
                </h6>

                <form method="POST" action="{{ route('customer.addresses.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Label</label>
                        <select name="label" class="form-select form-select-sm">
                            <option value="Home">🏠 Home</option>
                            <option value="Work">🏢 Work</option>
                            <option value="Other">📍 Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            Address Line 1 <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="address_line1"
                            class="form-control form-control-sm @error('address_line1') is-invalid @enderror"
                            value="{{ old('address_line1') }}" placeholder="Street, Building, Flat No." required>
                        @error('address_line1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Address Line 2</label>
                        <input type="text" name="address_line2" class="form-control form-control-sm"
                            value="{{ old('address_line2') }}" placeholder="Landmark, Area (optional)">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">
                                City <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="city"
                                class="form-control form-control-sm @error('city') is-invalid @enderror"
                                value="{{ old('city') }}" placeholder="City" required>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">
                                State <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="state"
                                class="form-control form-control-sm @error('state') is-invalid @enderror"
                                value="{{ old('state') }}" placeholder="State" required>
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            Pincode <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="pincode"
                            class="form-control form-control-sm @error('pincode') is-invalid @enderror"
                            value="{{ old('pincode') }}" placeholder="6-digit pincode" required>
                        @error('pincode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_default" value="1" id="isDefault">
                            <label class="form-check-label small" for="isDefault">
                                Set as default address
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-plus-lg me-1"></i>Add Address
                    </button>
                </form>
            </div>
        </div>

        {{-- Addresses List --}}
        <div class="col-lg-8">
            @if ($addresses->isEmpty())
                <div class="table-card p-5 text-center text-muted">
                    <i class="bi bi-geo-alt fs-1 d-block mb-3 opacity-25"></i>
                    No addresses yet. Add your first delivery address!
                </div>
            @else
                <div class="row g-3">
                    @foreach ($addresses as $address)
                        <div class="col-md-6">
                            <div class="table-card p-4 h-100 {{ $address->is_default ? 'border border-primary' : '' }}">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge badge-status" style="background:#FF6B35">
                                            @if ($address->label === 'Home')
                                                🏠
                                            @elseif($address->label === 'Work')
                                                🏢
                                            @else
                                                📍
                                            @endif
                                            {{ $address->label }}
                                        </span>
                                        @if ($address->is_default)
                                            <span class="badge bg-primary badge-status">
                                                Default
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Actions --}}
                                    <div class="dropdown">
                                        <button class="btn btn-xs btn-outline-secondary" data-bs-toggle="dropdown"
                                            style="font-size:11px;padding:3px 8px">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if (!$address->is_default)
                                                <li>
                                                    <form method="POST"
                                                        action="{{ route('customer.addresses.set-default', $address->id) }}">
                                                        @csrf
                                                        <button class="dropdown-item small">
                                                            <i class="bi bi-star me-2"></i>Set as Default
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                            <li>
                                                <button class="dropdown-item small"
                                                    onclick="editAddress({{ $address->id }},
                                                    '{{ $address->label }}',
                                                    '{{ $address->address_line1 }}',
                                                    '{{ $address->address_line2 }}',
                                                    '{{ $address->city }}',
                                                    '{{ $address->state }}',
                                                    '{{ $address->pincode }}')">
                                                    <i class="bi bi-pencil me-2"></i>Edit
                                                </button>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form method="POST"
                                                    action="{{ route('customer.addresses.destroy', $address->id) }}"
                                                    onsubmit="return confirm('Delete this address?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="dropdown-item small text-danger">
                                                        <i class="bi bi-trash me-2"></i>Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                {{-- Address Details --}}
                                <div class="small text-dark">{{ $address->address_line1 }}</div>
                                @if ($address->address_line2)
                                    <div class="small text-muted">{{ $address->address_line2 }}</div>
                                @endif
                                <div class="small text-muted">
                                    {{ $address->city }}, {{ $address->state }} — {{ $address->pincode }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Edit Address Modal --}}
    <div class="modal fade" id="editAddressModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-semibold">Edit Address</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editAddressForm" action = `/addresses/${id}`>
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Label</label>
                            <select name="label" id="editLabel" class="form-select form-select-sm">
                                <option value="Home">🏠 Home</option>
                                <option value="Work">🏢 Work</option>
                                <option value="Other">📍 Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Address Line 1</label>
                            <input type="text" name="address_line1" id="editLine1"
                                class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Address Line 2</label>
                            <input type="text" name="address_line2" id="editLine2"
                                class="form-control form-control-sm">
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold small">City</label>
                                <input type="text" name="city" id="editCity" class="form-control form-control-sm"
                                    required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold small">State</label>
                                <input type="text" name="state" id="editState" class="form-control form-control-sm"
                                    required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Pincode</label>
                            <input type="text" name="pincode" id="editPincode" class="form-control form-control-sm"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-primary">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function editAddress(id, label, line1, line2, city, state, pincode) {
            // Set form action
            document.getElementById('editAddressForm').action = `/addresses/${id}`;

            // Fill fields
            document.getElementById('editLabel').value = label;
            document.getElementById('editLine1').value = line1;
            document.getElementById('editLine2').value = line2 || '';
            document.getElementById('editCity').value = city;
            document.getElementById('editState').value = state;
            document.getElementById('editPincode').value = pincode;

            // Show modal
            new bootstrap.Modal(document.getElementById('editAddressModal')).show();
        }
    </script>
@endpush
