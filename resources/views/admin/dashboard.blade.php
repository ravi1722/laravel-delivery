@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
    {{-- Stats Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(255,107,53,.1);color:#FF6B35">
                        <i class="bi bi-shop"></i>
                    </div>
                    <div>
                        <div class="stat-value">10</div>
                        <div class="stat-label">Total Restaurants</div>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top d-flex justify-content-between">
                    <small class="text-warning">
                        <i class="bi bi-clock"></i>
                        5 pending
                    </small>
                    <small class="text-success">
                        <i class="bi bi-check-circle"></i>
                        6 active
                    </small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(40,167,69,.1);color:#28a745">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <div class="stat-value">165</div>
                        <div class="stat-label">Total Customers</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(0,123,255,.1);color:#007bff">
                        <i class="bi bi-bag"></i>
                    </div>
                    <div>
                        <div class="stat-value">1205</div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top">
                    <small class="text-muted">Today: <strong>5</strong></small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(255,193,7,.1);color:#ffc107">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                    <div>
                        <div class="stat-value">₹15024</div>
                        <div class="stat-label">Total Revenue</div>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top">
                    <small class="text-muted">Today:
                        <strong>₹1025</strong></small>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4">
        {{-- Pending Restaurants --}}
        <div class="col-lg-6">
            <div class="table-card">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-semibold mb-0">
                        <i class="bi bi-clock-history text-warning me-2"></i>
                        Pending Approvals
                    </h6>
                    <a href="#" class="btn btn-sm btn-outline-warning">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Restaurant</th>
                                <th>Owner</th>
                                <th>City</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">
                                    No pending approvals
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- Recent Orders --}}
        <div class="col-lg-6">
            <div class="table-card">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-semibold mb-0">
                        <i class="bi bi-bag text-primary me-2"></i>
                        Recent Orders
                    </h6>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No orders yet</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
