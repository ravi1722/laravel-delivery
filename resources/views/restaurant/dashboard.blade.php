@extends('layouts.app')

@section('title', 'Restaurant Dashboard')
@section('page-title', 'Restaurant Dashboard');

@section('content')
    {{-- Stats --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(255,107,53,.1);color:#FF6B35">
                        <i class="bi bi-bag"></i>
                    </div>
                    <div>
                        <div class="stat-value">11</div>
                        <div class="stat-label">Today's Orders</div>
                    </div>
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
                        <div class="stat-value">₹1485</div>
                        <div class="stat-label">Today's Revenue</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(220,53,69,.1);color:#dc3545">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <div class="stat-value">2</div>
                        <div class="stat-label">Pending Orders</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(40,167,69,.1);color:#28a745">
                        <i class="bi bi-egg-fried"></i>
                    </div>
                    <div>
                        <div class="stat-value">14</div>
                        <div class="stat-label">Menu Items</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4">
        {{-- Quick Actions --}}
        <div class="col-lg-4">
            <div class="table-card p-4">
                <h6 class="fw-semibold mb-3">Quick Actions</h6>
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-2"></i>Add Menu Item
                    </a>
                    <a href="#" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-list-ul me-2"></i>Manage Categories
                    </a>
                    <a href="#" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-pencil me-2"></i>Edit Profile
                    </a>
                </div>

                <hr>

                {{-- Restaurant Open/Close Toggle --}}
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="fw-semibold small">Restaurant Status</div>
                        <div class="text-muted" style="font-size:11px">
                            Toggle to open or close
                        </div>
                    </div>
                    <form method="POST" action="">
                        @csrf
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" role="switch" onchange="this.form.submit()"
                                style="width:48px;height:24px;cursor:pointer">
                        </div>
                    </form>
                </div>
                <div class="mt-2 text-center">
                    <span class="badge bg-success badge-status">'🟢 Currently Open'
                        {{-- {{ $restaurant->is_open ? '🟢 Currently Open' : '🔴 Currently Closed' }} --}}
                    </span>
                </div>
            </div>
        </div>
        {{-- Recent Orders --}}
        <div class="col-lg-8">
            <div class="table-card">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-semibold mb-0">Recent Orders</h6>
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
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">
                                    No orders yet
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
