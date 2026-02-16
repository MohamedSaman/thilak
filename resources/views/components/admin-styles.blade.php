<style>
    /* ========================================
       SHARED ADMIN UI STYLES
       Modern, Premium Design System
       ======================================== */

    /* Base Container */
    .container-fluid {
        background: linear-gradient(135deg, #f8f9ff 0%, #e8f0fe 100%);
        min-height: 100vh;
        padding: 1.5rem;
    }

    /* ========================================
       CARD COMPONENTS
       ======================================== */

    /* Page Header Card */
    .card-header {
        background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%);
        color: white;
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(35, 61, 127, 0.15);
        border: none;
        padding: 2rem;
    }

    .card-header.bg-transparent {
        background: transparent;
        color: inherit;
    }

    .icon-shape {
        width: 3.5rem;
        height: 3.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .icon-shape.icon-lg {
        width: 4rem;
        height: 4rem;
    }

    .icon-shape.icon-md {
        width: 2.5rem;
        height: 2.5rem;
    }

    /* Enhanced Cards */
    .card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 1rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        box-shadow: 0 8px 32px rgba(35, 61, 127, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(35, 61, 127, 0.12);
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Stat Cards */
    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        box-shadow: 0 8px 32px rgba(35, 61, 127, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 48px rgba(35, 61, 127, 0.12);
    }

    /* ========================================
       TABLES
       ======================================== */

    .table-responsive {
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(35, 61, 127, 0.08);
    }

    .table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table thead th {
        background: linear-gradient(135deg, #eff6ff 0%, #e0f2fe 100%);
        color: #233d7f;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border: none;
        padding: 1rem 0.75rem;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table tbody tr {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-bottom: 1px solid rgba(229, 231, 235, 0.5);
    }

    .table tbody tr:nth-child(even) {
        background-color: rgba(249, 250, 251, 0.5);
    }

    .table tbody tr:hover {
        background: linear-gradient(90deg, rgba(0, 200, 255, 0.05) 0%, rgba(35, 61, 127, 0.03) 100%);
        transform: scale(1.005);
        box-shadow: 0 4px 12px rgba(35, 61, 127, 0.08);
    }

    .table tbody td {
        padding: 1rem 0.75rem;
        color: #4b5563;
        vertical-align: middle;
        border: none;
    }

    /* ========================================
       BUTTONS
       ======================================== */

    .btn {
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 0.75rem;
        padding: 0.625rem 1.25rem;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #00C8FF 0%, #00a8d8 100%);
        box-shadow: 0 4px 12px rgba(0, 200, 255, 0.25);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #233d7f 0%, #1e3a8a 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(35, 61, 127, 0.3);
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.35);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6B7280 0%, #4b5563 100%);
        box-shadow: 0 4px 12px rgba(107, 114, 128, 0.2);
    }

    .btn-secondary:hover {
        background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(107, 114, 128, 0.3);
    }

    .btn-light {
        background: rgba(255, 255, 255, 0.95);
        color: #233d7f;
        border: 2px solid rgba(229, 231, 235, 0.8);
        box-shadow: 0 2px 8px rgba(35, 61, 127, 0.08);
    }

    .btn-light:hover {
        background: white;
        border-color: #00C8FF;
        color: #00C8FF;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 200, 255, 0.2);
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 0.5rem;
    }

    .rounded-full,
    .rounded-pill {
        border-radius: 9999px !important;
    }

    /* ========================================
       FORMS
       ======================================== */

    .form-control,
    .form-select {
        border: 2px solid rgba(229, 231, 235, 0.8);
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: rgba(255, 255, 255, 0.95);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #00C8FF;
        box-shadow: 0 0 0 4px rgba(0, 200, 255, 0.1);
        background: white;
        transform: translateY(-1px);
    }

    .form-label {
        font-weight: 600;
        color: #233d7f;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .input-group {
        box-shadow: 0 2px 8px rgba(35, 61, 127, 0.06);
        border-radius: 0.75rem;
        overflow: hidden;
    }

    .input-group-text {
        background: rgba(243, 244, 246, 0.8);
        border: 2px solid rgba(229, 231, 235, 0.8);
        border-right: none;
        color: #4b5563;
        font-weight: 500;
    }

    .input-group .form-control {
        border-left: none;
    }

    /* ========================================
       BADGES
       ======================================== */

    .badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.3px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .badge.bg-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .badge.bg-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .badge.bg-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        border: 1px solid rgba(245, 158, 11, 0.3);
        color: white !important;
    }

    .badge.bg-info {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important;
        border: 1px solid rgba(6, 182, 212, 0.3);
    }

    .badge.bg-primary {
        background: linear-gradient(135deg, #233d7f 0%, #1e3a8a 100%) !important;
        border: 1px solid rgba(35, 61, 127, 0.3);
    }

    /* ========================================
       MODALS
       ======================================== */

    .modal-content {
        border: 2px solid rgba(35, 61, 127, 0.2);
        border-radius: 1.25rem;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: 0 24px 64px rgba(35, 61, 127, 0.2);
    }

    .modal-header {
        background: linear-gradient(135deg, #233d7f 0%, #1e3a8a 100%);
        color: white;
        border-bottom: none;
        border-radius: 1.125rem 1.125rem 0 0;
        padding: 1.5rem 2rem;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        border-top: 1px solid rgba(229, 231, 235, 0.5);
        background: rgba(248, 249, 250, 0.8);
        border-radius: 0 0 1.125rem 1.125rem;
        padding: 1.25rem 2rem;
    }

    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.65);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }

    /* ========================================
       ALERTS
       ======================================== */

    .alert {
        border-radius: 1rem;
        border: none;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .alert-success {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.05) 100%);
        border-left: 4px solid #10b981;
        color: #065f46;
    }

    .alert-danger {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.05) 100%);
        border-left: 4px solid #ef4444;
        color: #991b1b;
    }

    .alert-warning {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.05) 100%);
        border-left: 4px solid #f59e0b;
        color: #92400e;
    }

    .alert-info {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(8, 145, 178, 0.05) 100%);
        border-left: 4px solid #06b6d4;
        color: #0c4a6e;
    }

    /* ========================================
       PAGINATION
       ======================================== */

    .pagination {
        margin-top: 1.5rem;
    }

    .page-link {
        color: #233d7f;
        border: 1px solid rgba(229, 231, 235, 0.8);
        border-radius: 0.5rem;
        margin: 0 0.25rem;
        padding: 0.5rem 0.75rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 500;
    }

    .page-link:hover {
        background: linear-gradient(135deg, rgba(0, 200, 255, 0.1) 0%, rgba(35, 61, 127, 0.05) 100%);
        border-color: #00C8FF;
        color: #233d7f;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 200, 255, 0.2);
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, #233d7f 0%, #1e3a8a 100%);
        border-color: #233d7f;
        box-shadow: 0 4px 12px rgba(35, 61, 127, 0.3);
    }

    /* ========================================
       UTILITY CLASSES
       ======================================== */

    .tracking-tight {
        letter-spacing: -0.025em;
    }

    .transition-all {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .hover\:scale-105:hover {
        transform: scale(1.05);
    }

    .hover\:shadow:hover {
        box-shadow: 0 10px 25px rgba(35, 61, 127, 0.15);
    }

    .hover\:bg-gray-50:hover {
        background-color: rgba(249, 250, 251, 0.8);
    }

    .shadow-sm {
        box-shadow: 0 2px 8px rgba(35, 61, 127, 0.06);
    }

    .shadow-lg {
        box-shadow: 0 8px 32px rgba(35, 61, 127, 0.12);
    }

    .shadow-xl {
        box-shadow: 0 16px 48px rgba(35, 61, 127, 0.16);
    }

    .text-sm {
        font-size: 0.875rem;
    }

    .text-xs {
        font-size: 0.75rem;
    }

    .fw-medium {
        font-weight: 500;
    }

    /* ========================================
       ANIMATIONS
       ======================================== */

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

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.5s ease-out;
    }

    .slide-in {
        animation: slideIn 0.4s ease-out;
    }

    /* ========================================
       RESPONSIVE
       ======================================== */

    @media (max-width: 768px) {
        .container-fluid {
            padding: 1rem;
        }

        .card-header {
            padding: 1.5rem;
            flex-direction: column !important;
            text-align: center;
        }

        .icon-shape.icon-lg {
            width: 3rem;
            height: 3rem;
        }

        .table {
            font-size: 0.875rem;
        }

        .table thead th,
        .table tbody td {
            padding: 0.75rem 0.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .modal-body {
            padding: 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .card-header {
            padding: 1.25rem;
        }

        .stat-card {
            padding: 1.25rem;
        }

        .table thead th,
        .table tbody td {
            padding: 0.625rem 0.375rem;
            font-size: 0.8125rem;
        }
    }
</style>
