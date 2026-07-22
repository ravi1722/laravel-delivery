<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Laravel') — Food Delivery</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --sidebar-width: 260px;
            --primary: #FF6B35;
            --primary-dark: #e55a25;
            --sidebar-bg: #1a1a2e;
            --sidebar-text: #a8a8b3;
            --sidebar-active: #FF6B35;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f8f9fa;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all .3s;
        }

        .sidebar-brand {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
        }

        .sidebar-brand h4 {
            color: var(--primary);
            font-weight: 700;
            margin: 0;
        }

        .sidebar-nav {
            padding: 10px 0;
        }

        .nav-section-title {
            color: rgba(255, 255, 255, .3);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 15px 20px 5px;
        }

        .sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 0;
            transition: all .2s;
            font-size: 14px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255, 107, 53, .15);
            border-left: 3px solid var(--primary);
        }

        .sidebar .nav-link i {
            font-size: 18px;
            width: 20px;
        }

        /* Main content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all .3s;
        }

        /* Top navbar */
        .top-navbar {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .05);
        }

        .content-area {
            padding: 24px;
        }

        /* Cards */
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #e9ecef;
            transition: all .2s;
        }

        .stat-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
        }

        .stat-card .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a2e;
            line-height: 1;
        }

        .stat-card .stat-label {
            font-size: 13px;
            color: #6c757d;
            margin-top: 4px;
        }

        /* Notification bell */
        .notification-bell {
            position: relative;
            cursor: pointer;
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 18px;
            height: 18px;
            background: var(--primary);
            border-radius: 50%;
            font-size: 10px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Alerts */
        .alert {
            border-radius: 10px;
            border: none;
        }

        /* Buttons */
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        /* Tables */
        .table-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            overflow: hidden;
        }

        .table-card .table {
            margin: 0;
        }

        .table-card .table th {
            background: #f8f9fa;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #6c757d;
            font-weight: 600;
            border-bottom: 1px solid #e9ecef;
        }

        /* Badges */
        .badge-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    @auth
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <h4><i class="bi bi-lightning-charge-fill"></i> Laravel-Delivery</h4>
                <small class="text-muted" style="font-size:11px">Food Delivery Platform</small>
            </div>
            <nav class="sidebar-nav">
                @include('partials.sidebar-' . auth()->user()->role)
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navbar -->
            <div class="top-navbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-sm btn-light d-md-none" id="sidebarToggle">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <h6 class="mb-0 fw-semibold text-dark">@yield('page-title', 'Dashboard')</h6>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <!-- Notification Bell -->
                    <div class="notification-bell" id="notificationBell">
                        <i class="bi bi-bell fs-5 text-secondary"></i>
                        <span class="notification-badge" id="notificationCount">0</span>
                    </div>

                    <!-- User Menu -->
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle d-flex align-items-center gap-2"
                            data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()->avatar }}" width="28" height="28"
                                class="rounded-circle object-fit-cover" alt="Avatar">
                            <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            <div class="px-4 pt-3">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                {{-- @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif --}}
            </div>

            <!-- Page Content -->
            <div class="content-area">
                @yield('content')
            </div>
        </div>
    @else
        @yield('content')
    @endauth

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        // Sidebar toggle (mobile)
        $('#sidebarToggle').click(function() {
            $('#sidebar').toggleClass('open');
        });

        // Active nav link
        const currentPath = window.location.pathname;
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });

        // CSRF token for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
