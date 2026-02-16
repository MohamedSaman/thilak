<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Page Title' }}</title>

    <!-- jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Then Livewire/Alpine -->
    <script src="livewire.js?id=df3a17f2"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @livewireStyles
    <style>
        :root {
            --primary-blue: #233d7f;
            --primary-blue-dark: #1e3a8a;
            --accent-cyan: #00C8FF;
            --accent-cyan-dark: #00a8d8;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-600: #4b5563;
            --gray-800: #1f2937;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }

        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8f9ff 0%, #e8f0fe 100%);
            color: var(--gray-800);
            min-height: 100vh;
        }

        /* Enhanced Sidebar with Glassmorphism */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            color: #333;
            position: fixed;
            border-right: 1px solid rgba(229, 231, 235, 0.5);
            overflow-y: auto;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1040;
            box-shadow: 4px 0 24px rgba(35, 61, 127, 0.08);
        }

        .sidebar-header {
            padding: 28px 25px;
            font-size: 1.25rem;
            font-weight: 800;
            text-align: center;
            letter-spacing: -0.03em;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            border-bottom: 2px solid rgba(35, 61, 127, 0.1);
            position: relative;
        }

        .sidebar-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--accent-cyan), transparent);
            border-radius: 2px;
        }

        .sidebar-section-title {
            padding: 18px 20px 10px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #9ca3af;
            letter-spacing: 1px;
            position: relative;
        }

        .nav-link {
            color: var(--gray-600);
            padding: 13px 20px;
            border-radius: 0;
            margin: 2px 8px;
            display: flex;
            align-items: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 3px solid transparent;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, rgba(0, 200, 255, 0.1) 0%, rgba(35, 61, 127, 0.05) 100%);
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: -1;
        }

        .nav-link i {
            margin-right: 14px;
            font-size: 1.1rem;
            color: #9ca3af;
            width: 22px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-link:hover {
            background: linear-gradient(90deg, rgba(0, 200, 255, 0.08) 0%, rgba(35, 61, 127, 0.05) 100%);
            color: var(--primary-blue);
            transform: translateX(4px);
            border-left-color: var(--accent-cyan);
        }

        .nav-link:hover::before {
            width: 100%;
        }

        .nav-link:hover i {
            color: var(--accent-cyan);
            transform: scale(1.1);
        }

        .nav-link.active {
            background: linear-gradient(90deg, rgba(0, 200, 255, 0.15) 0%, rgba(35, 61, 127, 0.08) 100%);
            color: var(--primary-blue);
            font-weight: 600;
            border-left-color: var(--accent-cyan);
            box-shadow: 0 2px 8px rgba(0, 200, 255, 0.2);
        }

        .nav-link.active i {
            color: var(--accent-cyan);
        }

        /* Collapsed Sidebar */
        .sidebar.collapsed {
            width: 80px;
        }

        /* Adjust text visibility when collapsed */
        .sidebar.collapsed .nav-link span {
            display: none;
            /* hide menu text */
        }

        .sidebar.collapsed .sidebar-section-title {
            display: none;
        }

        .sidebar.collapsed .sidebar-title img {
            width: 60px;
            /* shrink logo */
            height: auto;
        }

        .sidebar.collapsed .sidebar-title h3 {
            display: none;
            /* hide title text */
        }

        /* Enhanced Top Bar with Glassmorphism */
        .top-bar {
            height: 72px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.5);
            padding: 0 28px;
            position: fixed;
            top: 0;
            left: 260px;
            right: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            width: calc(100% - 260px);
            box-shadow: 0 4px 20px rgba(35, 61, 127, 0.06);
        }

        .top-bar.collapsed {
            left: 80px;
            width: calc(100% - 80px);
        }

        .top-bar .btn {
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .top-bar .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(35, 61, 127, 0.15);
        }

        /* Enhanced Admin Info */
        .admin-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 14px;
            border-radius: 50px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .admin-info:hover {
            background: linear-gradient(135deg, rgba(0, 200, 255, 0.08) 0%, rgba(35, 61, 127, 0.05) 100%);
            box-shadow: 0 4px 12px rgba(35, 61, 127, 0.1);
        }

        .admin-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-dark) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(35, 61, 127, 0.25);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .admin-info:hover .admin-avatar {
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(35, 61, 127, 0.35);
        }

        .admin-name {
            font-weight: 600;
            color: var(--gray-800);
        }

        /* Enhanced Dropdown Menu */
        .dropdown-menu {
            box-shadow: 0 12px 40px rgba(35, 61, 127, 0.15);
            border: 1px solid rgba(229, 231, 235, 0.5);
            border-radius: 16px;
            padding: 12px;
            margin-top: 12px;
            min-width: 220px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .dropdown-item {
            padding: 10px 16px;
            display: flex;
            align-items: center;
            color: var(--gray-600);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 10px;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background: linear-gradient(90deg, rgba(0, 200, 255, 0.12) 0%, rgba(35, 61, 127, 0.08) 100%);
            color: var(--primary-blue);
            transform: translateX(4px);
        }

        .dropdown-item i {
            font-size: 1.1rem;
            margin-right: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dropdown-item:hover i {
            color: var(--accent-cyan);
        }

        /* Enhanced Main Content */
        .main-content {
            margin-left: 270px;
            margin-top: 72px;
            padding: 24px;
            min-height: calc(100vh - 72px);
            width: calc(100% - 270px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main-content.collapsed {
            margin-left: 90px;
            width: calc(100% - 90px);
        }

        /* Enhanced Card Styles */
        .stat-card,
        .widget-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(35, 61, 127, 0.08);
            border: 1px solid rgba(229, 231, 235, 0.5);
            padding: 1.5rem;
            height: 100%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:hover,
        .widget-container:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 48px rgba(35, 61, 127, 0.12);
        }

        /* Enhanced Button Styles */
        .btn {
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 10px;
            padding: 0.625rem 1.25rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent-cyan) 0%, #00a8d8 100%);
            border: none;
            box-shadow: 0 4px 12px rgba(0, 200, 255, 0.25);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-dark) 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(35, 61, 127, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            border: none;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.35);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6B7280 0%, #4b5563 100%);
            border: none;
            box-shadow: 0 4px 12px rgba(107, 114, 128, 0.2);
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(107, 114, 128, 0.3);
        }

        /* Enhanced Table Styles */
        .table {
            border-radius: 12px;
            overflow: hidden;
        }

        .table thead th {
            background: linear-gradient(135deg, #eff6ff 0%, #e0f2fe 100%);
            color: var(--primary-blue);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border: none;
            padding: 1rem;
        }

        .table-hover tbody tr {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .table-hover tbody tr:hover {
            background: linear-gradient(90deg, rgba(0, 200, 255, 0.05) 0%, rgba(35, 61, 127, 0.03) 100%);
            transform: scale(1.01);
            box-shadow: 0 4px 12px rgba(35, 61, 127, 0.08);
        }

        /* Enhanced Modal Styles */
        .modal-content {
            border: 2px solid rgba(35, 61, 127, 0.2);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 24px 64px rgba(35, 61, 127, 0.2);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-dark) 100%);
            color: white;
            border-bottom: none;
            border-radius: 18px 18px 0 0;
            padding: 1.5rem 2rem;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .modal-footer {
            border-top: 1px solid rgba(229, 231, 235, 0.5);
            background: rgba(248, 249, 250, 0.8);
            border-radius: 0 0 18px 18px;
            padding: 1.25rem 2rem;
        }

        /* Responsive Styles */
        @media (max-width: 930px) {
            .sidebar {
                transform: translateX(-100%);
                width: 250px;
                height: 100%;
                bottom: 0;
                top: 0;
                overflow-y: auto;
            }

            .sidebar.show {
                transform: translateX(0);
                box-shadow: 4px 0 32px rgba(35, 61, 127, 0.2);
            }

            .top-bar {
                width: 100%;
                left: 0;
            }

            .top-bar .title {
                display: none;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 16px;
            }

            .header {
                display: none;
            }
        }

        /* Enhanced Form Controls */
        .form-control,
        .form-select {
            border: 2px solid rgba(229, 231, 235, 0.8);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.95);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent-cyan);
            box-shadow: 0 0 0 4px rgba(0, 200, 255, 0.1);
            background: white;
            transform: translateY(-1px);
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        /* Enhanced Input Groups */
        .input-group {
            box-shadow: 0 2px 8px rgba(35, 61, 127, 0.06);
            border-radius: 12px;
            overflow: hidden;
        }

        .input-group-text {
            background: rgba(243, 244, 246, 0.8);
            border: 2px solid rgba(229, 231, 235, 0.8);
            border-right: none;
            color: var(--gray-600);
            font-weight: 500;
        }

        .input-group .form-control {
            border-left: none;
        }

        /* Enhanced Badges */
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 0.3px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(243, 244, 246, 0.5);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--accent-cyan) 0%, var(--primary-blue) 100%);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-dark) 100%);
        }

        /* Smooth Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.8;
            }
        }

        /* Loading States */
        .loading {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Utility Classes */
        .tracking-tight {
            letter-spacing: -0.025em;
        }

        .transition-all {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .rounded-full {
            border-radius: 9999px;
        }

        .shadow-soft {
            box-shadow: 0 4px 16px rgba(35, 61, 127, 0.08);
        }

        .shadow-medium {
            box-shadow: 0 8px 24px rgba(35, 61, 127, 0.12);
        }

        .shadow-strong {
            box-shadow: 0 16px 48px rgba(35, 61, 127, 0.16);
        }

        /* Glassmorphism Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(35, 61, 127, 0.1);
        }

        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-cyan) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Enhanced Alert */
        .alert {
            border-radius: 16px;
            border: none;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.05) 100%);
            border-left: 4px solid var(--success);
            color: #065f46;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.05) 100%);
            border-left: 4px solid var(--danger);
            color: #991b1b;
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.05) 100%);
            border-left: 4px solid var(--warning);
            color: #92400e;
        }

        /* Page Transitions */
        .page-enter {
            animation: fadeInUp 0.5s ease-out;
        }

        .nav-link {
            animation: slideInRight 0.3s ease-out;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-title">
                    {{-- <img src="{{ asset('images/plus.png') }}" alt="Logo" width="200px" height="100px"> --}}
                    <h3 class="text-center" style="font-family: 'monospace'; font-weight: 800; font-size: 1rem; margin: 0;">THILAK HARDWARE</h3>
                </div>
            </div>
            <ul class="nav flex-column">
                <!-- GENERAL Section -->
                <li class="sidebar-section-title">General</li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-bar-chart-line"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 {{ request()->routeIs('admin.manage-customer') ? 'active' : '' }}"
                        href="{{ route('admin.manage-customer') }}">
                        <i class="bi bi-people"></i> <span>Customers</span>
                    </a>
                </li>

                <!-- INVENTORY Section -->
                <li class="sidebar-section-title">Inventory</li>
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" href="#inventorySubmenu" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="inventorySubmenu">
                        <i class="bi bi-box-seam"></i> <span>Products</span>
                    </a>
                    <div class="collapse" id="inventorySubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link py-2 {{ request()->routeIs('admin.products') ? 'active' : '' }}"
                                    href="{{ route('admin.products') }}">
                                    <i class="bi bi-box-fill"></i> <span>Manage Products</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-2 {{ request()->routeIs('admin.categories') ? 'active' : '' }}"
                                    href="{{ route('admin.categories') }}">
                                    <i class="bi bi-collection"></i> <span>Categories</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-2 {{ request()->routeIs('admin.brands') ? 'active' : '' }}"
                                    href="{{ route('admin.brands') }}">
                                    <i class="bi bi-tag"></i> <span>Brands</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 {{ request()->routeIs('admin.product-stocks') ? 'active' : '' }}"
                        href="{{ route('admin.product-stocks') }}">
                        <i class="bi bi-shield-lock"></i> <span>Stock Management</span>
                    </a>
                </li>

                <!-- FINANCIALS Section -->
                <li class="sidebar-section-title">Financials</li>
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" href="#salesSubmenu" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="salesSubmenu">
                        <i class="bi bi-cart"></i> <span>Sales</span>
                    </a>
                    <div class="collapse" id="salesSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link py-2 {{ request()->routeIs('admin.customer-sale-details') ? 'active' : '' }}"
                                    href="{{ route('admin.customer-sale-details') }}">
                                    <i class="bi bi-people"></i> <span>Customer Sale</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-2 {{ request()->routeIs('admin.view-invoice') ? 'active' : '' }}"
                                    href="{{ route('admin.view-invoice') }}">
                                    <i class="bi bi-credit-card-2-back"></i> <span>Invoices</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" href="#chequeSubmenu" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="chequeSubmenu">
                        <i class="bi bi-wallet2"></i> <span>Cheque / Payment</span>
                    </a>
                    <div class="collapse" id="chequeSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link py-2 {{ request()->routeIs('admin.due-payments') ? 'active' : '' }}"
                                    href="{{ route('admin.due-payments') }}">
                                    <i class="bi bi-cash-coin"></i> <span>Due Payments</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-2 {{ request()->routeIs('admin.due-cheques') ? 'active' : '' }}"
                                    href="{{ route('admin.due-cheques') }}">
                                    <i class="bi bi-receipt"></i> <span>Cheque Details</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-2 {{ request()->routeIs('admin.due-cheques-return') ? 'active' : '' }}"
                                    href="{{ route('admin.due-cheques-return') }}">
                                    <i class="bi bi-arrow-counterclockwise"></i> <span>Cheque Return</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 {{ request()->routeIs('admin.store-billing') ? 'active' : '' }}"
                        href="{{ route('admin.store-billing') }}" target="_blank">
                        <i class="bi bi-cash"></i> <span>Billing</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 {{ request()->routeIs('admin.reports') ? 'active' : '' }}"
                        href="{{ route('admin.reports') }}">
                        <i class="bi bi-graph-up"></i> <span>Reports</span>
                    </a>
                </li>

            </ul>
        </div>

        <!-- Top Navigation Bar -->
        <nav class="top-bar d-flex align-items-center px-3">
            <!-- Sidebar Toggle -->
            <button id="sidebarToggler" class="btn btn-light rounded-pill me-3 transition-all hover:shadow"
                style="border: 1px solid #e5e7eb; background: white; padding: 8px 12px;">
                <i class="bi bi-list fs-5" style="color: #4b5563;"></i>
            </button>

            <!-- Search Bar -->
            <!-- <div class="flex-grow-1" style="max-width: 400px;">
                <input type="text" class="form-control" placeholder="Search..."
                    style="border: 1px solid #e5e7eb; border-radius: 8px; background: #f8f9fa; padding: 8px 12px;">
            </div> -->

            <!-- Right Dropdown -->
            <div class="ms-auto dropdown">
                <div class="admin-info dropdown-toggle" id="adminDropdown" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <div class="admin-avatar">A</div>
                    <div class="admin-name">Admin</div>
                </div>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-person me-2"></i>My Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-gear me-2"></i>Settings
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="mb-0">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>


        <!-- Main Content -->
        <main class="main-content">
            {{ $slot }}
        </main>
    </div>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggler = document.getElementById('sidebarToggler');
            const sidebar = document.querySelector('.sidebar');
            const topBar = document.querySelector('.top-bar');
            const mainContent = document.querySelector('.main-content');

            function initializeSidebar() {
                const sidebarCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
                if (sidebarCollapsed && window.innerWidth >= 768) {
                    sidebar.classList.add('collapsed');
                    topBar.classList.add('collapsed');
                    mainContent.classList.add('collapsed');
                }
                if (window.innerWidth < 768) {
                    sidebar.classList.remove('show');
                    topBar.classList.remove('collapsed');
                    mainContent.classList.remove('collapsed');
                }
            }

            function toggleSidebar(event) {
                if (event) {
                    event.stopPropagation();
                }
                if (window.innerWidth < 768) {
                    sidebar.classList.toggle('show');
                } else {
                    sidebar.classList.toggle('collapsed');
                    topBar.classList.toggle('collapsed');
                    mainContent.classList.toggle('collapsed');
                    localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
                }
            }

            if (sidebarToggler && sidebar) {
                initializeSidebar();
                sidebarToggler.addEventListener('click', toggleSidebar);
                document.addEventListener('click', function(event) {
                    if (window.innerWidth < 768 &&
                        sidebar.classList.contains('show') &&
                        !sidebar.contains(event.target) &&
                        !sidebarToggler.contains(event.target)) {
                        sidebar.classList.remove('show');
                    }
                });
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 768) {
                        sidebar.classList.remove('show');
                        const sidebarCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
                        if (sidebarCollapsed) {
                            sidebar.classList.add('collapsed');
                            topBar.classList.add('collapsed');
                            mainContent.classList.add('collapsed');
                        } else {
                            sidebar.classList.remove('collapsed');
                            topBar.classList.remove('collapsed');
                            mainContent.classList.remove('collapsed');
                        }
                    } else {
                        topBar.classList.remove('collapsed');
                        mainContent.classList.remove('collapsed');
                    }
                });
            }

            function setActiveMenuItem() {
                const currentPath = window.location.pathname;
                document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                    link.classList.remove('active');
                });
                document.querySelectorAll('.collapse').forEach(submenu => {
                    submenu.classList.remove('show');
                });
                document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
                    toggle.setAttribute('aria-expanded', 'false');
                });

                let activeFound = false;
                document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                    const href = link.getAttribute('href');
                    if (href && href !== '#' && !href.startsWith('#')) {
                        const hrefPath = href.replace(/^(https?:\/\/[^\/]+)/, '').split('?')[0];
                        if (currentPath === hrefPath) {
                            link.classList.add('active');
                            activeFound = true;
                            const submenu = link.closest('.collapse');
                            if (submenu) {
                                submenu.classList.add('show');
                                const parentToggle = document.querySelector(`[href="#${submenu.id}"]`);
                                if (parentToggle) {
                                    parentToggle.classList.add('active');
                                    parentToggle.setAttribute('aria-expanded', 'true');
                                }
                            }
                        }
                    }
                });

                if (!activeFound) {
                    document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                        const href = link.getAttribute('href');
                        if (href && href !== '#' && !href.startsWith('#')) {
                            const hrefPath = href.replace(/^(https?:\/\/[^\/]+)/, '').split('?')[0];
                            if (hrefPath !== '/' && currentPath.includes(hrefPath)) {
                                link.classList.add('active');
                                const submenu = link.closest('.collapse');
                                if (submenu) {
                                    submenu.classList.add('show');
                                    const parentToggle = document.querySelector(`[href="#${submenu.id}"]`);
                                    if (parentToggle) {
                                        parentToggle.classList.add('active');
                                        parentToggle.setAttribute('aria-expanded', 'true');
                                    }
                                }
                            }
                        }
                    });
                }
            }

            setActiveMenuItem();
            window.addEventListener('resize', adjustSidebarHeight);

            function adjustSidebarHeight() {
                const sidebar = document.querySelector('.sidebar');
                const windowHeight = window.innerHeight;
                if (sidebar) {
                    sidebar.style.height = windowHeight + 'px';
                    const sidebarContent = sidebar.querySelector('.nav.flex-column');
                    if (sidebarContent && sidebarContent.scrollHeight > windowHeight) {
                        sidebar.classList.add('scrollable');
                    } else {
                        sidebar.classList.remove('scrollable');
                    }
                }
            }

            adjustSidebarHeight();
        });
    </script>
    @stack('scripts')
</body>

</html>