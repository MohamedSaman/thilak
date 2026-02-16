<style>
    /* ========================================
       SHARED ADMIN UI STYLES - MODERN PREMIUM
       ======================================== */

    :root {
        /* Color Palette */
        --admin-primary: #233d7f;
        --admin-primary-light: #3152a5;
        --admin-primary-dark: #1a2d5e;
        --admin-accent: #00c8ff;
        --admin-accent-soft: rgba(0, 200, 255, 0.1);
        --admin-success: #10b981;
        --admin-danger: #ef4444;
        --admin-warning: #f59e0b;
        --admin-info: #06b6d4;
        
        /* Grays */
        --admin-bg: #f8faff;
        --admin-card-bg: rgba(255, 255, 255, 0.95);
        --admin-gray-50: #f9fafb;
        --admin-gray-100: #f3f4f6;
        --admin-gray-200: #e5e7eb;
        --admin-gray-300: #d1d5db;
        --admin-gray-600: #646971ff;
        --admin-gray-800: #1f2937;
        
        /* Shadows & Borders */
        --admin-shadow-sm: 0 2px 4px rgba(35, 61, 127, 0.05);
        --admin-shadow-md: 0 8px 16px rgba(35, 61, 127, 0.08);
        --admin-shadow-lg: 0 16px 32px rgba(35, 61, 127, 0.12);
        --admin-radius: 1rem;
        --admin-radius-sm: 0.5rem;
        --admin-radius-lg: 1.5rem;
    }

    /* Base Layout */
    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        background-color: var(--admin-bg);
        color: var(--admin-gray-800);
        -webkit-font-smoothing: antialiased;
        overflow-x: hidden;
    }

    .container-fluid {
        padding: 1.5rem;
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ========================================
       TYPOGRAPHY
       ======================================== */
    h1, h2, h3, h4, h5, h6 {
        font-weight: 700;
        letter-spacing: -0.02em;
        color: var(--admin-primary-dark);
    }

    .text-sm { font-size: 0.875rem; }
    .text-xs { font-size: 0.75rem; }
    .fw-medium { font-weight: 500; }
    .fw-semibold { font-weight: 600; }

    /* ========================================
       CARD COMPONENTS
       ======================================== */
    .card {
        background: var(--admin-card-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(229, 231, 235, 0.5);
        border-radius: var(--admin-radius);
        box-shadow: var(--admin-shadow-md);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        margin-bottom: 1.5rem;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: var(--admin-shadow-lg);
    }

    /* Page Header Component */
    .page-header {
        background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-primary-dark) 100%);
        border-radius: var(--admin-radius);
        padding: 2.5rem 2rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        color: white;
        box-shadow: 0 10px 30px rgba(35, 61, 127, 0.2);
        position: relative;
        overflow: hidden;
    }

    .page-header::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(0, 200, 255, 0.2) 0%, transparent 70%);
        pointer-events: none;
    }

    .page-header-icon {
        width: 3.5rem;
        height: 3.5rem;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1.5rem;
        font-size: 1.75rem;
        backdrop-filter: blur(5px);
    }

    .page-header-content h2 {
        color: white;
        margin-bottom: 0.25rem;
        font-size: 1.75rem;
    }

    .page-header-content p {
        margin-bottom: 0;
        opacity: 0.9;
        font-size: 0.95rem;
    }

    /* Action Bar (Search, Filters, Add Button) */
    .action-bar {
        background: white;
        border-radius: var(--admin-radius);
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
        box-shadow: var(--admin-shadow-sm);
        border: 1px solid var(--admin-gray-100);
    }

    /* ========================================
       STAT CARDS
       ======================================== */
    .stat-card {
        padding: 1.5rem;
        border-radius: var(--admin-radius);
        background: white;
        position: relative;
        overflow: hidden;
    }

    .stat-card .icon-placeholder {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        width: 3rem;
        height: 3rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        background: var(--admin-accent-soft);
        color: var(--admin-accent);
    }

    .stat-card-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--admin-gray-600);
        margin-bottom: 0.5rem;
    }

    .stat-card-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--admin-primary-dark);
        margin-bottom: 0.5rem;
    }

    /* Icon Shapes */
    .icon-shape {
        width: 3rem;
        height: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.75rem;
        font-size: 1.25rem;
        transition: all 0.3s ease;
    }

    .icon-lg {
        width: 4rem;
        height: 4rem;
        font-size: 1.75rem;
        border-radius: 1.25rem;
    }

    .icon-sm {
        width: 2.25rem;
        height: 2.25rem;
        font-size: 1rem;
        border-radius: 0.5rem;
    }

    /* Soft Backgrounds */
    .bg-primary-soft { background-color: rgba(35, 61, 127, 0.1) !important; color: var(--admin-primary) !important; }
    .bg-success-soft { background-color: rgba(16, 185, 129, 0.1) !important; color: var(--admin-success) !important; }
    .bg-danger-soft { background-color: rgba(239, 68, 68, 0.1) !important; color: var(--admin-danger) !important; }
    .bg-warning-soft { background-color: rgba(245, 158, 11, 0.1) !important; color: var(--admin-warning) !important; }
    .bg-info-soft { background-color: rgba(6, 182, 212, 0.1) !important; color: var(--admin-info) !important; }
    .bg-indigo-soft { background-color: rgba(79, 70, 229, 0.1) !important; color: #4f46e5 !important; }
    .bg-light-soft { background-color: #f8faff !important; }

    /* ========================================
       TABLES
       ======================================== */
    .table-container {
        background: white;
        border-radius: var(--admin-radius);
        overflow: hidden;
        box-shadow: var(--admin-shadow-sm);
        border: 1px solid var(--admin-gray-100);
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background: var(--admin-gray-50);
        color: var(--admin-primary);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 1.25rem 1rem;
        border-bottom: 1px solid var(--admin-gray-200);
    }

    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
        color: var(--admin-gray-800);
        border-bottom: 1px solid var(--admin-gray-100);
    }

    .table tbody tr:hover {
        background-color: var(--admin-gray-50);
    }

    /* Action Buttons in Table */
    .table-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }

    .btn-action {
        width: 2.25rem;
        height: 2.25rem;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        transition: all 0.2s;
        border: none;
    }

    .btn-action-view { color: var(--admin-primary); background: rgba(35, 61, 127, 0.1); }
    .btn-action-view:hover { background: var(--admin-primary); color: white; }

    .btn-action-edit { color: var(--admin-accent); background: rgba(0, 200, 255, 0.1); }
    .btn-action-edit:hover { background: var(--admin-accent); color: white; }

    .btn-action-delete { color: var(--admin-danger); background: rgba(239, 68, 68, 0.1); }
    .btn-action-delete:hover { background: var(--admin-danger); color: white; }

    /* ========================================
       FORMS
       ======================================== */
    .form-label {
        font-weight: 600;
        color: var(--admin-primary-dark);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-control, .form-select {
        border: 1.5px solid var(--admin-gray-200);
        border-radius: 0.75rem;
        padding: 0.625rem 1rem;
        font-size: 0.9375rem;
        transition: all 0.2s;
        background: white;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--admin-accent);
        box-shadow: 0 0 0 4px var(--admin-accent-soft);
        outline: none;
    }

    /* ========================================
       BUTTONS
       ======================================== */
    .btn {
        border-radius: 0.75rem;
        font-weight: 600;
        padding: 0.625rem 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--admin-accent) 0%, #0099cc 100%);
        border: none;
        color: white;
        box-shadow: 0 4px 12px rgba(0, 200, 255, 0.3);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-primary-dark) 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(35, 61, 127, 0.4);
        color: white;
    }

    .btn-secondary {
        background: var(--admin-gray-100);
        border: 1px solid var(--admin-gray-200);
        color: var(--admin-gray-800);
    }

    .btn-secondary:hover {
        background: var(--admin-gray-200);
        color: var(--admin-gray-800);
        transform: translateY(-1px);
    }

    /* ========================================
     MODALS
     ======================================== */
    .modal-content {
        border: none;
        border-radius: var(--admin-radius-lg);
        overflow: hidden;
        box-shadow: var(--admin-shadow-lg);
    }

    .modal-header {
        background: var(--admin-primary);
        color: white;
        padding: 1.5rem 2rem;
        border-bottom: none;
    }

    .modal-body {
        padding: 2.5rem 2rem;
    }

    .modal-footer {
        background: var(--admin-gray-50);
        padding: 1.25rem 2rem;
        border-top: 1px solid var(--admin-gray-200);
    }

    /* ========================================
       AVATARS
       ======================================== */
    .avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        vertical-align: middle;
        position: relative;
        flex-shrink: 0;
    }

    .avatar-sm { width: 2rem; height: 2rem; font-size: 0.875rem; }
    .avatar-md { width: 2.5rem; height: 2.5rem; font-size: 1rem; }
    .avatar-lg { width: 3.5rem; height: 3.5rem; font-size: 1.25rem; }
    .avatar-xl { width: 5rem; height: 5rem; font-size: 1.5rem; }

    .object-cover { object-fit: cover; }
    
    /* ========================================
       BADGES
       ======================================== */
    .badge {
        padding: 0.5em 1.25em;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.7rem;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Soft Badges */
    .badge-primary-soft { background-color: rgba(35, 61, 127, 0.1); color: var(--admin-primary); }
    .badge-success-soft { background-color: rgba(16, 185, 129, 0.1); color: var(--admin-success); }
    .badge-danger-soft { background-color: rgba(239, 68, 68, 0.1); color: var(--admin-danger); }
    .badge-warning-soft { background-color: rgba(245, 158, 11, 0.1); color: var(--admin-warning); }
    .badge-info-soft { background-color: rgba(6, 182, 212, 0.1); color: var(--admin-info); }
    .badge-indigo-soft { background-color: rgba(79, 70, 229, 0.1); color: #4f46e5; }
    .badge-secondary-soft { background-color: var(--admin-gray-100); color: var(--admin-gray-600); }

    .badge-pill { border-radius: 999px; }

    /* ========================================
       UTILITIES
       ======================================== */
    .glass-card {
        background: var(--admin-card-bg) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        border: 1px solid rgba(229, 231, 235, 0.5) !important;
    }

    .shadow-premium { box-shadow: 0 10px 30px rgba(35, 61, 127, 0.08) !important; }
    
    .rounded-xl { border-radius: var(--admin-radius); }
    .rounded-2xl { border-radius: var(--admin-radius-lg); }

    /* Custom Scrollbar */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: var(--admin-gray-50); }
    ::-webkit-scrollbar-thumb { background: var(--admin-gray-300); border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--admin-gray-600); }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .page-header { padding: 1.5rem; flex-direction: column; text-align: center; }
        .page-header-icon { margin-right: 0; margin-bottom: 1rem; }
        .action-bar { flex-direction: column; align-items: stretch; }
        .stat-card-value { font-size: 1.25rem; }
    }
</style>
