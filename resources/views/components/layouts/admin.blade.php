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
    @include('components.admin-styles')
    <style>
        /* Layout Specific Styles */
        .sidebar {
            width: 280px;
            height: 100vh;
            background: white;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1040;
            border-right: 1px solid var(--admin-gray-200);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 24px rgba(35, 61, 127, 0.04);
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid var(--admin-gray-100);
        }

        .sidebar-brand {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--admin-primary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-nav {
            padding: 1.5rem 1rem;
            flex-grow: 1;
            overflow-y: auto;
        }

        .nav-section-title {
            font-size: 0.5rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--admin-gray-600);
            letter-spacing: 0.1em;
            margin: 1.5rem 0 0.75rem 0.75rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            color: var(--admin-gray-600);
            border-radius: 0.75rem;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 0.25rem;
            text-decoration: none;
            gap: 0.75rem;
        }

        .nav-link i {
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
            transition: all 0.2s;
        }

        .nav-link:hover {
            background: var(--admin-gray-50);
            color: var(--admin-primary);
            transform: translateX(4px);
        }

        .nav-link.active {
            background: var(--admin-accent-soft);
            color: var(--admin-primary);
            font-weight: 700;
        }

        .nav-link.active i {
            color: var(--admin-accent);
        }

        .top-bar {
            height: 72px;
            position: fixed;
            top: 0;
            right: 0;
            left: 280px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--admin-gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            transition: all 0.3s;
        }

        .main-content {
            margin-left: 280px;
            padding-top: 72px;
            min-height: 100vh;
            transition: all 0.3s;
        }

        /* Responsive Sidebar */
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); width: 260px; }
            .sidebar.show { transform: translateX(0); }
            .top-bar { left: 0; }
            .main-content { margin-left: 0; }
        }

        /* Profile Dropdown */
        .admin-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 100px;
            transition: all 0.2s;
            cursor: pointer;
            border: 1px solid transparent;
        }

        .admin-profile:hover {
            background: white;
            border-color: var(--admin-gray-200);
            box-shadow: var(--admin-shadow-sm);
        }

        .admin-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--admin-primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-brand">
                <i class="bi bi-cpu text-accent"></i>
                <span>Thilak Hardware</span>
            </div>
        </div>
        
        <div class="sidebar-nav">
            <!-- GENERAL Section -->
            <div class="nav-section-title">General</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.manage-customer') }}" class="nav-link {{ request()->routeIs('admin.manage-customer') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Customers</span>
            </a>

            <!-- INVENTORY Section -->
            <div class="nav-section-title">Inventory</div>
            <a href="{{ route('admin.products') }}" class="nav-link {{ request()->routeIs('admin.products') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span>Products</span>
            </a>
            <a href="{{ route('admin.categories') }}" class="nav-link {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
                <i class="bi bi-collection"></i>
                <span>Categories</span>
            </a>
            <a href="{{ route('admin.brands') }}" class="nav-link {{ request()->routeIs('admin.brands') ? 'active' : '' }}">
                <i class="bi bi-tag"></i>
                <span>Brands</span>
            </a>
            <a href="{{ route('admin.product-stocks') }}" class="nav-link {{ request()->routeIs('admin.product-stocks') ? 'active' : '' }}">
                <i class="bi bi-database"></i>
                <span>Stock Management</span>
            </a>

            <!-- FINANCIALS Section -->
            <div class="nav-section-title">Financials</div>
            <a href="{{ route('admin.customer-sale-details') }}" class="nav-link {{ request()->routeIs('admin.customer-sale-details') ? 'active' : '' }}">
                <i class="bi bi-cart"></i>
                <span>Sales</span>
            </a>
            <a href="{{ route('admin.view-invoice') }}" class="nav-link {{ request()->routeIs('admin.view-invoice') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i>
                <span>Invoices</span>
            </a>
            
            <div class="nav-section-title">Payments</div>
            <a href="{{ route('admin.due-payments') }}" class="nav-link {{ request()->routeIs('admin.due-payments') ? 'active' : '' }}">
                <i class="bi bi-wallet2"></i>
                <span>Due Payments</span>
            </a>
            <a href="{{ route('admin.due-cheques') }}" class="nav-link {{ request()->routeIs('admin.due-cheques') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i>
                <span>Cheque Details</span>
            </a>

            <div class="nav-section-title">System</div>
            <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Reports</span>
            </a>
            <a href="{{ route('admin.store-billing') }}" target="_blank" class="nav-link">
                <i class="bi bi-cash-stack"></i>
                <span>Billing System</span>
            </a>
        </div>
    </div>

    <!-- Top Navigation -->
    <header class="top-bar">
        <button id="sidebarToggler" class="btn btn-secondary px-2 py-1 rounded-circle">
            <i class="bi bi-list"></i>
        </button>

        <div class="ms-auto d-flex align-items-center gap-3">
            <div class="dropdown">
                <div class="admin-profile" id="profileDropdown" data-bs-toggle="dropdown">
                    <div class="admin-avatar">A</div>
                    <div class="d-none d-md-block">
                        <div class="fw-bold text-sm">Administrator</div>
                        <div class="text-xs text-muted">Admin Panel</div>
                    </div>
                    <i class="bi bi-chevron-down text-xs text-muted ms-1"></i>
                </div>
                <ul class="dropdown-menu dropdown-menu-end shadow-premium border-0 rounded-xl" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item py-2" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item py-2" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
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