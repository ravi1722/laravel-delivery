@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'My Dashboard')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(255,107,53,.1); color: #FF6B35">
                        <i class="bi bi-bag"></i>
                    </div>
                    <div>
                        <div class="stat-value">0</div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(40,167,69,.1); color: #28a745">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div>
                        <div class="stat-value">₹0</div>
                        <div class="stat-label">Wallet Balance</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(0,123,255,.1); color: #007bff">
                        <i class="bi bi-star"></i>
                    </div>
                    <div>
                        <div class="stat-value">0</div>
                        <div class="stat-label">Reviews Given</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(255,193,7,.1); color: #ffc107">
                        <i class="bi bi-heart"></i>
                    </div>
                    <div>
                        <div class="stat-value">0</div>
                        <div class="stat-label">Saved Restaurants</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="table-card">
        <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-semibold mb-0">Recent Orders</h6>
            <a href="#" class="btn btn-sm btn-primary">View All</a>
        </div>
        <div class="p-4 text-center text-muted">
            <i class="bi bi-bag-x fs-1 d-block mb-2 opacity-25"></i>
            No orders yet. Start ordering your favorite food!
            <br>
            <a href="#" class="btn btn-primary mt-3">Browse Restaurants</a>
        </div>
    </div>
@endsection
