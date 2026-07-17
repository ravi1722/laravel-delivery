@extends('layouts.auth')
@section('title', 'Register')
@section('content')
    <div class="auth-card">
        <div class="text-center mb-4">
            <h4 class="fw-bold" style="color: var(--primary)">
                <i class="bi bi-lightning-charge-fill"></i> Laravel-Delivery
            </h4>
            <h5 class="fw-semibold mt-3">Create your account</h5>
            <p class="text-muted small">Join thousands of food lovers</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>• {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Full Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" placeholder="Enter your full name" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" placeholder="Enter your email" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Phone Number</label>
                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                    value="{{ old('phone') }}" placeholder="10-digit mobile number" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">I am a</label>
                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="">Select your role</option>
                    <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>
                        Customer — I want to order food
                    </option>
                    <option value="restaurant_owner" {{ old('role') === 'restaurant_owner' ? 'selected' : '' }}>
                        Restaurant Owner — I want to list my restaurant
                    </option>
                    <option value="delivery_agent" {{ old('role') === 'delivery_agent' ? 'selected' : '' }}>
                        Delivery Agent — I want to deliver food
                    </option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="Min 8 characters" required>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm your password"
                    required>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                Create Account
            </button>

            <p class="text-center mt-3 text-muted small">
                Already have an account?
                <a href="{{ route('login') }}" style="color: var(--primary)">Login here</a>
            </p>

        </form>
    </div>
@endsection
