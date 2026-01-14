<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
    <meta name="user-id" content="{{ auth()->id() }}">
    @endauth
    <title>@yield('title', 'GCMS - ระบบจัดการคลินิกไกสิริ')</title>

    <!-- Thai Font (Kanit) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- GCMS Blue-White-Navy Professional Theme -->
    <link href="{{ asset('css/gcms-blue-theme.css') }}" rel="stylesheet">

    @stack('styles')

    <style>
        /* BLUE-WHITE-NAVY THEME STYLES */
        body {
            font-family: 'Kanit', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg-secondary) !important;
            color: var(--text-primary);
        }

        /* Minimal White Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: #ffffff;
            padding: 24px 16px;
            padding-bottom: 40px;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 1px 0 3px rgba(0, 0, 0, 0.05);
            border-right: 1px solid #f1f5f9;
            overflow-y: scroll;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior: contain;
        }

        /* Scrollbar Styling */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #f8fafc;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Logo Section */
        .sidebar .logo-section {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            border: 1px solid #e2e8f0;
        }

        .sidebar .logo-section img {
            opacity: 0.9;
            transition: all 0.3s ease;
        }

        .sidebar .logo-section:hover img {
            opacity: 1;
            transform: scale(1.02);
        }

        .sidebar h5 {
            color: #1e293b;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .sidebar small {
            color: #64748b;
            font-size: 0.875rem;
        }

        .sidebar .nav-link {
            color: #475569;
            padding: 10px 16px;
            margin: 2px 0;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            font-weight: 400;
            position: relative;
        }

        .sidebar .nav-link i {
            color: #94a3b8;
            transition: color 0.2s ease;
        }

        .sidebar .nav-link:hover {
            background: #f1f5f9;
            color: #0ea5e9;
        }

        .sidebar .nav-link:hover i {
            color: #0ea5e9;
        }

        .sidebar .nav-link.active {
            background: #f0f9ff;
            color: #0284c7;
            font-weight: 500;
            border-left: 3px solid #0ea5e9;
        }

        .sidebar .nav-link.active i {
            color: #0ea5e9;
        }

        .sidebar hr {
            border: none;
            border-top: 1px solid #f1f5f9;
            margin: 16px 0;
        }

        /* Dropdown submenu */
        .sidebar #settingsSubmenu {
            background: #f8fafc;
            border-radius: 8px;
            margin: 4px 0;
            padding: 4px 0;
        }

        .sidebar #settingsSubmenu .nav-link {
            font-size: 0.85rem;
            padding: 8px 12px 8px 32px;
            color: #64748b;
        }

        .sidebar #settingsSubmenu .nav-link:hover {
            color: #0ea5e9;
            background: #f0f9ff;
        }

        /* Blue Theme Top Navbar */
        .navbar-top {
            margin-left: 260px;
            background: var(--theme-white);
            border-bottom: 2px solid var(--theme-sky-100);
            box-shadow: 0 2px 8px rgba(30, 58, 138, 0.05);
            padding: 0 24px;
            height: 64px;
        }

        .navbar-top .btn-link {
            color: var(--theme-navy-600);
            text-decoration: none;
            font-weight: 500;
        }

        .navbar-top .btn-link:hover {
            color: var(--theme-ocean-600);
        }

        .navbar-top .dropdown-menu {
            border: 1px solid var(--theme-sky-200);
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.1);
            border-radius: 8px;
        }

        /* Main Content Area */
        .main-content {
            margin-left: 260px;
            padding: 32px;
            min-height: 100vh;
            background: var(--bg-secondary);
        }

        /* Blue Theme Cards */
        .card {
            background: var(--card-bg) !important;
            border: 1px solid var(--card-border) !important;
            border-radius: 12px !important;
            box-shadow: var(--card-shadow) !important;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--theme-sky-50) 0%, var(--theme-sky-100) 100%) !important;
            border-bottom: 2px solid var(--theme-ocean-200) !important;
            padding: 20px 24px !important;
            color: var(--theme-navy-700);
        }

        .card-body {
            padding: 24px !important;
            background: var(--theme-white) !important;
        }

        /* Blue Theme Buttons */
        .btn {
            font-weight: 500;
            border-radius: 8px;
            padding: 10px 20px;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--theme-sky-500) 0%, var(--theme-ocean-600) 100%) !important;
            color: var(--theme-white) !important;
            border: none !important;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--theme-sky-600) 0%, var(--theme-ocean-700) 100%) !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(37, 99, 235, 0.3);
        }

        .btn-secondary {
            background: var(--theme-white) !important;
            color: var(--theme-ocean-600) !important;
            border: 2px solid var(--theme-ocean-300) !important;
        }

        .btn-secondary:hover {
            background: var(--theme-sky-50) !important;
            border-color: var(--theme-ocean-500) !important;
        }

        /* Blue Theme Tables */
        .table {
            background: var(--theme-white) !important;
            border-radius: 8px !important;
            overflow: hidden !important;
        }

        .table thead th {
            background: linear-gradient(180deg, var(--theme-sky-100) 0%, var(--theme-sky-50) 100%) !important;
            border-bottom: 2px solid var(--theme-ocean-200) !important;
            border-top: none !important;
            color: var(--theme-navy-700) !important;
            font-weight: 600 !important;
            font-size: 0.875rem !important;
            padding: 14px 16px !important;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .table tbody td {
            border-bottom: 1px solid var(--theme-sky-100) !important;
            padding: 16px !important;
            color: var(--text-primary);
        }

        .table tbody tr:hover {
            background: var(--theme-sky-50) !important;
        }

        /* Blue Theme Forms */
        .form-control, .form-select {
            background: var(--theme-white) !important;
            border: 2px solid var(--theme-sky-200) !important;
            border-radius: 8px !important;
            padding: 10px 14px !important;
            font-size: 0.95rem !important;
            color: var(--theme-navy-700) !important;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--theme-ocean-500) !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            background: var(--theme-white) !important;
        }

        .form-control::placeholder {
            color: var(--theme-gray-400) !important;
        }

        .form-label {
            color: var(--theme-navy-600) !important;
            font-size: 0.9rem !important;
            font-weight: 600 !important;
            margin-bottom: 6px !important;
        }

        /* Blue Theme Alerts */
        .alert {
            border: none !important;
            border-radius: 8px !important;
            padding: 14px 20px !important;
            border-left: 4px solid;
        }

        .alert-success {
            background: linear-gradient(90deg, #ecfdf5 0%, #d1fae5 100%) !important;
            color: #065f46 !important;
            border-left-color: #10b981 !important;
        }

        .alert-danger {
            background: linear-gradient(90deg, #fef2f2 0%, #fee2e2 100%) !important;
            color: #991b1b !important;
            border-left-color: #ef4444 !important;
        }

        .alert-info {
            background: linear-gradient(90deg, var(--theme-sky-50) 0%, var(--theme-sky-100) 100%) !important;
            color: var(--theme-navy-700) !important;
            border-left-color: var(--theme-ocean-500) !important;
        }

        /* Blue Theme Badge */
        .badge {
            font-weight: 500 !important;
            padding: 6px 12px !important;
            border-radius: 20px !important;
        }

        .bg-primary {
            background: linear-gradient(135deg, var(--theme-sky-500) 0%, var(--theme-ocean-600) 100%) !important;
        }

        .text-primary {
            color: var(--theme-ocean-600) !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                padding-top: 20px;
                padding-bottom: 100px;
                height: 100vh;
                height: 100dvh;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar .logo-section {
                margin-top: 0 !important;
            }

            .sidebar nav {
                padding-bottom: 20px;
            }

            .main-content,
            .navbar-top {
                margin-left: 0;
            }

            .main-content {
                padding-bottom: 70px;
            }
        }

        /* Mobile Bottom Navigation */
        .mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: #fff;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-around;
            align-items: center;
            z-index: 1000;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        }

        .mobile-bottom-nav a {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #64748b;
            font-size: 0.65rem;
            padding: 6px 12px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .mobile-bottom-nav a i {
            font-size: 1.25rem;
            margin-bottom: 2px;
        }

        .mobile-bottom-nav a.active {
            color: #0ea5e9;
        }

        .mobile-bottom-nav a.active i {
            color: #0ea5e9;
        }

        .mobile-bottom-nav a:active {
            background: #f0f9ff;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="logo-section text-center" style="margin-top: 40px;">
            <h5 class="mb-1">กายสิริ</h5>
            <small>คลินิกกายภาพบำบัด</small>
        </div>

        <nav class="nav flex-column">
            {{-- Menu for All Users --}}
            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i> แดชบอร์ด
            </a>
            <a class="nav-link {{ request()->is('patients*') ? 'active' : '' }}" href="{{ route('patients.index') }}">
                <i class="bi bi-people me-2"></i> คนไข้
            </a>
            <a class="nav-link {{ request()->is('appointments*') ? 'active' : '' }}" href="{{ route('appointments.index') }}">
                <i class="bi bi-calendar-check me-2"></i> นัดหมาย
            </a>
            <a class="nav-link {{ request()->is('queue*') ? 'active' : '' }}" href="{{ route('queue.index') }}">
                <i class="bi bi-list-ol me-2"></i> คิว
            </a>

            {{-- PT Menu: My Income --}}
            @if(auth()->check() && auth()->user()->role && auth()->user()->role->name === 'PT')
            <a class="nav-link {{ request()->is('commission-rates/'.auth()->id().'/detail*') ? 'active' : '' }}" href="{{ url('/commission-rates/'.auth()->id().'/detail') }}">
                <i class="bi bi-cash-coin me-2"></i> รายได้ของฉัน
            </a>
            @endif

            {{-- Admin/Manager Menu --}}
            @if(auth()->check() && (auth()->user()->username === 'admin' || (auth()->user()->role && in_array(auth()->user()->role->name, ['Admin', 'Manager']))))
            <a class="nav-link {{ request()->is('reports*') ? 'active' : '' }}" href="{{ url('/reports/pnl') }}">
                <i class="bi bi-graph-up me-2"></i> รายงาน P&L
            </a>
            <a class="nav-link {{ request()->is('expenses*') ? 'active' : '' }}" href="{{ url('/expenses') }}">
                <i class="bi bi-receipt me-2"></i> รายจ่าย
            </a>
            <a class="nav-link {{ request()->is('stock*') ? 'active' : '' }}" href="{{ url('/stock') }}">
                <i class="bi bi-box-seam me-2"></i> จัดการสต็อก
            </a>
            <a class="nav-link {{ request()->is('equipment*') ? 'active' : '' }}" href="{{ url('/equipment') }}">
                <i class="bi bi-tools me-2"></i> จัดการอุปกรณ์
            </a>
            <a class="nav-link {{ request()->is('crm') ? 'active' : '' }}" href="{{ url('/crm') }}">
                <i class="bi bi-telephone me-2"></i> CRM
            </a>
            @endif

            {{-- Admin Only: System Settings --}}
            @if(auth()->check() && auth()->user()->username === 'admin')
            <hr class="text-white-50 mx-3 my-2">
            <div class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button" data-bs-toggle="collapse" data-bs-target="#settingsSubmenu" aria-expanded="false">
                    <i class="bi bi-gear me-2"></i> ตั้งค่าระบบ
                </a>
                <div class="collapse" id="settingsSubmenu">
                    <nav class="nav flex-column ms-3">
                        <a class="nav-link {{ request()->is('services*') ? 'active' : '' }}" href="{{ url('/services') }}">
                            <i class="bi bi-clipboard-pulse me-2"></i> จัดการบริการ
                        </a>
                        <a class="nav-link {{ request()->is('course-packages*') ? 'active' : '' }}" href="{{ url('/course-packages') }}">
                            <i class="bi bi-box-seam me-2"></i> จัดการคอร์ส
                        </a>
                        <a class="nav-link {{ request()->is('commission-rates*') && !request()->is('commission-rates/'.auth()->id().'/detail*') ? 'active' : '' }}" href="{{ url('/commission-rates') }}">
                            <i class="bi bi-cash-coin me-2"></i> จัดการค่าตอบแทน
                        </a>
                        <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="{{ url('/users') }}">
                            <i class="bi bi-people me-2"></i> จัดการพนักงาน
                        </a>
                        <a class="nav-link {{ request()->is('branches*') ? 'active' : '' }}" href="{{ url('/branches') }}">
                            <i class="bi bi-building me-2"></i> จัดการสาขา
                        </a>
                        <a class="nav-link {{ request()->is('roles*') || request()->is('permissions*') ? 'active' : '' }}" href="{{ url('/roles') }}">
                            <i class="bi bi-shield-lock me-2"></i> จัดการสิทธิ์
                        </a>
                    </nav>
                </div>
            </div>
            @endif
        </nav>
    </div>

    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-top sticky-top">
        <div class="container-fluid">
            <button class="btn btn-link d-md-none" type="button" id="sidebarToggle">
                <i class="bi bi-list fs-4"></i>
            </button>

            <div class="ms-auto d-flex align-items-center">
                {{-- Branch Switcher (Admin/Manager Only) --}}
                @if(auth()->check() && (auth()->user()->username === 'admin' || (auth()->user()->role && in_array(auth()->user()->role->name, ['Admin', 'Manager']))))
                    @if(auth()->user()->username === 'admin')
                    <a href="{{ route('branch.selector') }}" class="btn btn-sm btn-outline-primary me-3" title="สลับสาขา">
                        <i class="bi bi-building me-1"></i>
                        <span class="d-none d-md-inline">
                            {{ session('selected_branch_id') ? \App\Models\Branch::find(session('selected_branch_id'))->name : 'เลือกสาขา' }}
                        </span>
                        <i class="bi bi-arrow-repeat ms-1"></i>
                    </a>
                    @else
                    <span class="me-3 text-muted">
                        <i class="bi bi-building me-1"></i>
                        <span class="d-none d-md-inline">{{ auth()->user()->branch->name ?? 'สาขาหลัก' }}</span>
                    </span>
                    @endif
                @elseif(auth()->check() && auth()->user()->role && auth()->user()->role->name === 'PT')
                {{-- PT: Show branch only, no switch button --}}
                <span class="me-3 text-muted">
                    <i class="bi bi-building me-1"></i>
                    <span class="d-none d-md-inline">{{ auth()->user()->branch->name ?? 'สาขาหลัก' }}</span>
                </span>
                @endif

                <div class="dropdown">
                    <a class="btn btn-link text-decoration-none dropdown-toggle" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle fs-5 me-1"></i>
                        <span class="d-none d-md-inline">{{ auth()->check() ? auth()->user()->username : 'ผู้เยี่ยมชม' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i> ตั้งค่า</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right me-2"></i> ออกจากระบบ
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>ข้อผิดพลาด:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav d-md-none">
        <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="bi bi-house"></i>
            <span>หน้าแรก</span>
        </a>
        <a href="{{ route('patients.index') }}" class="{{ request()->is('patients*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            <span>คนไข้</span>
        </a>
        <a href="{{ route('appointments.index') }}" class="{{ request()->is('appointments*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i>
            <span>นัดหมาย</span>
        </a>
        <a href="{{ route('queue.index') }}" class="{{ request()->is('queue*') ? 'active' : '' }}">
            <i class="bi bi-list-ol"></i>
            <span>คิว</span>
        </a>
    </nav>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar toggle for mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');

                // Prevent body scroll when sidebar is open on mobile
                if (sidebar.classList.contains('show')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });

            // Close sidebar when clicking outside
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
                    if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        sidebar.classList.remove('show');
                        document.body.style.overflow = '';
                    }
                }
            });
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>
