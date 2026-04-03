<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Voucher System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            line-height: 1.6;
        }
        
        /* Layout Container */
        .layout-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar.collapsed {
            width: 80px;
        }
        
        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 20px;
            font-weight: 700;
            white-space: nowrap;
        }
        
        .sidebar-logo i {
            font-size: 24px;
            color: #667eea;
        }
        
        .sidebar.collapsed .sidebar-logo span {
            display: none;
        }
        
        .sidebar-toggle {
            background: rgba(255,255,255,0.1);
            border: none;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        
        .sidebar-toggle:hover {
            background: rgba(255,255,255,0.2);
        }
        
        .sidebar-menu {
            padding: 20px 0 160px 0;
            list-style: none;
        }
        
        .sidebar-menu-item {
            margin-bottom: 4px;
        }
        
        .sidebar-menu-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.2s;
            font-size: 14px;
            font-weight: 500;
            position: relative;
        }
        
        .sidebar-menu-link i {
            width: 20px;
            text-align: center;
            font-size: 18px;
        }
        
        .sidebar-menu-link span {
            white-space: nowrap;
        }
        
        .sidebar.collapsed .sidebar-menu-link span {
            display: none;
        }
        
        .sidebar-menu-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar-menu-link.active {
            background: linear-gradient(90deg, rgba(102,126,234,0.2) 0%, transparent 100%);
            color: white;
        }
        
        .sidebar-menu-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #667eea;
            border-radius: 0 4px 4px 0;
        }
        
        .sidebar-divider {
            height: 1px;
            background: rgba(255,255,255,0.1);
            margin: 12px 20px;
        }
        
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }
        
        .sidebar-user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            flex-shrink: 0;
        }
        
        .sidebar-user-info {
            flex: 1;
            min-width: 0;
        }
        
        .sidebar.collapsed .sidebar-user-info {
            display: none;
        }
        
        .sidebar.collapsed .sidebar-footer .btn-logout {
            padding: 10px;
            width: 100%;
        }
        
        .sidebar.collapsed .sidebar-logout-text {
            display: none;
        }
        
        .sidebar-user-name {
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .sidebar-user-role {
            font-size: 12px;
            color: rgba(255,255,255,0.6);
        }
        
        /* Header */
        .header {
            background: white;
            padding: 0 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
            transition: all 0.3s ease;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
        }
        
        .header-title {
            font-size: 20px;
            font-weight: 600;
            color: #0f172a;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .btn-logout {
            background: #ef4444;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-logout:hover {
            background: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239,68,68,0.3);
        }
        
        /* Main Wrapper */
        .main-wrapper {
            flex: 1;
            margin-left: 260px;
            transition: margin-left 0.3s ease;
        }
        
        .sidebar.collapsed ~ .main-wrapper {
            margin-left: 80px;
        }
        
        /* Content */
        .content {
            max-width: 1600px;
            margin: 0 auto;
            padding: 30px;
        }
        
        /* Page Header */
        .page-header {
            margin-bottom: 30px;
        }
        
        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }
        
        .page-subtitle {
            color: #64748b;
            font-size: 15px;
        }
        
        /* Card */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 25px;
            margin-bottom: 20px;
        }
        
        .card-header {
            font-size: 18px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f1f5f9;
        }
        
        /* Buttons */
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        
        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5568d3;
        }
        
        .btn-success {
            background: #10b981;
            color: white;
        }
        
        .btn-success:hover {
            background: #059669;
        }
        
        .btn-danger {
            background: #ef4444;
            color: white;
        }
        
        .btn-danger:hover {
            background: #dc2626;
        }
        
        .btn-warning {
            background: #f59e0b;
            color: white;
        }
        
        .btn-warning:hover {
            background: #d97706;
        }
        
        .btn-secondary {
            background: #64748b;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #475569;
        }
        
        .btn-sm {
            padding: 6px 14px;
            font-size: 13px;
        }
        
        /* Table */
        .table-container {
            overflow-x: auto;
            margin: 0 -25px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: #f8fafc;
        }
        
        th {
            padding: 14px 25px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        td {
            padding: 16px 25px;
            border-top: 1px solid #f1f5f9;
            font-size: 14px;
        }
        
        tbody tr {
            transition: background 0.15s;
        }
        
        tbody tr:hover {
            background: #f8fafc;
        }
        
        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }
        
        .badge-warning {
            background: #fed7aa;
            color: #92400e;
        }
        
        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .badge-secondary {
            background: #e2e8f0;
            color: #475569;
        }
        
        /* Pagination */
        .pagination-wrap {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 24px;
        }

        .pagination-meta {
            color: #64748b;
            font-size: 13px;
            font-weight: 500;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .pagination .page-link {
            padding: 8px 14px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            color: #475569;
            background: white;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s;
            min-width: 40px;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .pagination .page-link:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .pagination .page-link.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .pagination .page-link.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        @media (max-width: 640px) {
            .pagination-wrap {
                flex-direction: column;
                align-items: stretch;
            }

            .pagination {
                justify-content: flex-start;
            }
        }
        
        /* Alerts */
        .alert {
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }
        
        .alert-error, .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
        
        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border-left: 4px solid #3b82f6;
        }
        
        .alert-warning {
            background: #fed7aa;
            color: #92400e;
            border-left: 4px solid #f59e0b;
        }
        
        /* Forms */
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            color: #334155;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        input[type="text"],
        input[type="number"],
        input[type="email"],
        input[type="password"],
        select,
        textarea {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.2s;
        }
        
        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .help-text {
            color: #64748b;
            font-size: 13px;
            margin-top: 6px;
        }
        
        .error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 4px;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }
        
        .icon-green { background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); }
        .icon-blue { background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); }
        .icon-purple { background: linear-gradient(135deg, #e9d5ff 0%, #d8b4fe 100%); }
        .icon-orange { background: linear-gradient(135deg, #fed7aa 0%, #fdba74 100%); }
        
        .stat-content {
            flex: 1;
        }
        
        .stat-label {
            font-size: 13px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 4px;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #94a3b8;
        }
        
        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }
        
        /* Loading */    
        .loading {
            text-align: center;
            padding: 40px;
        }
        
        .spinner {
            border: 3px solid #f3f4f6;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Mobile Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        
        .sidebar-overlay.active {
            display: block;
        }
        
        .mobile-menu-toggle {
            display: none;
            background: #667eea;
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            
            .main-wrapper,
            .header {
                margin-left: 0 !important;
            }
            
            .mobile-menu-toggle {
                display: flex;
            }
            
            .sidebar-toggle {
                display: none;
            }
        }
        
        @media (max-width: 768px) {
            .content {
                padding: 15px;
            }
            
            .table-container {
                margin: 0 -15px;
            }
            
            th, td {
                padding: 12px 15px;
            }
            
            .header {
                padding: 0 15px;
            }
            
            .header-content {
                height: 60px;
            }
            
            .header-title {
                font-size: 16px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="layout-container">
        <!-- Mobile Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="fas fa-shield-alt"></i>
                    <span>Admin Panel</span>
                </div>
                <button class="sidebar-toggle" id="sidebarToggle" title="Toggle Sidebar">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <ul class="sidebar-menu">
                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.dashboard') }}"
                       class="sidebar-menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                       title="Dashboard">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <div class="sidebar-divider"></div>

                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.packages.index') }}"
                       class="sidebar-menu-link {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}"
                       title="Paket">
                        <i class="fas fa-box"></i>
                        <span>Paket Internet</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.vouchers.index') }}"
                       class="sidebar-menu-link {{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}"
                       title="Voucher">
                        <i class="fas fa-ticket-alt"></i>
                        <span>Voucher</span>
                    </a>
                </li>

                <div class="sidebar-divider"></div>

                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.transactions.index') }}"
                       class="sidebar-menu-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}"
                       title="Transaksi">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Transaksi</span>
                    </a>
                </li>
            </ul>

            <!-- Sidebar Footer (User Info) -->
            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="sidebar-user-avatar">
                        <i class="fas fa-user" style="font-size:16px;"></i>
                    </div>
                    <div class="sidebar-user-info">
                        <div class="sidebar-user-name">{{ session('admin_username', 'Admin') }}</div>
                        <div class="sidebar-user-role">Administrator</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn-logout" style="width:100%;justify-content:center;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="sidebar-logout-text">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="main-wrapper" id="mainWrapper">
            <!-- Top Header Bar -->
            <header class="header">
                <div class="header-content">
                    <div style="display:flex;align-items:center;gap:14px;">
                        <button class="mobile-menu-toggle" id="mobileMenuToggle">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h1 class="header-title">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    <div class="header-right">
                        <span id="serverClock" data-server-ts="{{ now()->timestamp }}" style="font-size:13px;color:#64748b;">
                            <i class="fas fa-clock"></i>
                            {{ now()->format('d M Y H:i:s') }}
                        </span>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="content">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <strong>Terdapat kesalahan:</strong>
                            <ul style="margin: 8px 0 0 20px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Sidebar toggle (collapse/expand)
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        // Restore saved state
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('collapsed');
        }

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        });

        mobileMenuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('mobile-open');
            sidebarOverlay.classList.toggle('active');
        });

        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('mobile-open');
            sidebarOverlay.classList.remove('active');
        });

        // Live server clock (increments every second without page refresh)
        const serverClock = document.getElementById('serverClock');
        if (serverClock) {
            let currentTs = parseInt(serverClock.dataset.serverTs || '0', 10);
            if (!Number.isNaN(currentTs) && currentTs > 0) {
                const formatter = new Intl.DateTimeFormat('en-GB', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                });

                const renderClock = () => {
                    const text = formatter.format(new Date(currentTs * 1000)).replace(',', '');
                    serverClock.innerHTML = '<i class="fas fa-clock"></i> ' + text;
                    currentTs += 1;
                };

                renderClock();
                setInterval(renderClock, 1000);
            }
        }

        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>
