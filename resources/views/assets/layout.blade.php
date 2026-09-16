<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise IT Asset Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body { 
            background-color: #f1f5f9; /* Warna abu-abu corporate */
            color: #334155;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 0.9rem;
            overflow-x: hidden;
        }

/* --- BODY & PAGE TRANSITION ANIMATION --- */
    body {
        background-color: #f1f5f9 !important;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    main, .content-wrapper, .card-dashboard, .card-ui {
        animation: fadeInUp 0.35s ease-out forwards;
    }

    /* --- SIDEBAR ENTERPRISE --- */
    .sidebar {
        width: 260px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        background-color: #0f172a;
        color: #94a3b8;
        transition: all 0.3s ease-in-out;
        z-index: 1040;
        display: flex;
        flex-direction: column;
        box-shadow: 4px 0 15px rgba(0, 0, 0, 0.05);
    }
    
    .sidebar-brand {
        padding: 24px 20px;
        font-size: 1.15rem;
        font-weight: 700;
        color: #ffffff;
        border-bottom: 1px solid #1e293b;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .sidebar-nav {
        padding: 20px 12px;
        flex-grow: 1;
        overflow-y: auto;
    }

    .nav-link-sidebar {
        color: #94a3b8;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.25s ease-in-out;
    }

    .nav-link-sidebar:hover {
        background-color: #1e293b !important;
        color: #ffffff !important;
        transform: translateX(4px);
    }

    .nav-link-sidebar.active { 
        background-color: #4f46e5 !important; 
        color: #ffffff !important; 
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35); 
    }
    
    .sidebar-footer { 
        padding: 16px 20px; 
        border-top: 1px solid #1e293b; 
        font-size: 0.8rem; 
        text-align: center; 
        color: #64748b;
    }
        /* --- MAIN CONTENT AREA --- */
        .main-wrapper {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease-in-out;
        }

        .top-navbar {
            height: 70px;
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 30px;
            justify-content: space-between;
        }

        .content-area { padding: 30px; flex-grow: 1; }

        /* Mobile Adjustments */
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .mobile-toggle { display: block !important; }
        }

        .mobile-toggle { display: none; background: none; border: none; font-size: 1.5rem; color: #334155; cursor: pointer; }

        /* --- COMPONENT STYLES --- */
        .card-ui { background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); padding: 20px; }
        .input-ui { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 8px 14px; font-size: 0.85rem; }
        .input-ui:focus { border-color: #94a3b8; outline: none; box-shadow: 0 0 0 3px rgba(226, 232, 240, 0.5); }
        
        .table-ui { width: 100%; border-collapse: separate; border-spacing: 0; }
        .table-ui thead th { background-color: #f8fafc; color: #475569; font-weight: 600; padding: 14px 16px; border-bottom: 2px solid #e2e8f0; font-size: 0.85rem; white-space: nowrap; }
        .table-ui tbody td { padding: 14px 16px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        
        .btn-action { border: none; border-radius: 6px; padding: 8px 16px; font-weight: 500; font-size: 0.85rem; color: #fff; text-decoration: none; transition: 0.2s;}
        .btn-action-primary { background-color: #3b82f6; } .btn-action-primary:hover { background-color: #2563eb; color: white;}
        .btn-action-success { background-color: #10b981; } .btn-action-success:hover { background-color: #059669; color: white;}
        .btn-action-warning { background-color: #f59e0b; } .btn-action-warning:hover { background-color: #d97706; color: white;}
        .btn-action-danger { background-color: #ef4444; } .btn-action-danger:hover { background-color: #dc2626; color: white;}
        .btn-action-info { background-color: #0ea5e9; } .btn-action-info:hover { background-color: #0284c7; color: white;}
    </style>
</head>
<body>

<!-- Sidebar Kiri -->
<aside class="sidebar d-flex flex-column" id="sidebar">
    
    <!-- Brand / Logo -->
    <div class="sidebar-brand d-flex align-items-center gap-3">
        <div class="rounded d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px; background-color: rgba(79, 70, 229, 0.2); color: #818cf8;">
            <i class="bi bi-box-seam fs-5"></i>
        </div>
        <span class="fw-bold" style="letter-spacing: -0.3px; color: #ffffff;">IT Asset Management</span>
    </div>

    <!-- Navigasi Menu -->
    <div class="sidebar-nav flex-grow-1">
        <span class="text-uppercase fw-bold mb-2 d-block px-2" style="font-size: 0.65rem; letter-spacing: 1px; color: #64748b;">Menu Utama</span>
        
        <a href="{{ route('assets.dashboard') }}" class="nav-link-sidebar {{ request()->routeIs('assets.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill fs-6"></i> Dashboard
        </a>
        
        <a href="{{ route('assets.index') }}" class="nav-link-sidebar {{ request()->routeIs('assets.index') || request()->routeIs('assets.show') || request()->routeIs('assets.edit') || request()->routeIs('assets.create') ? 'active' : '' }}">
            <i class="bi bi-hdd-network-fill fs-6"></i> Data Inventaris
        </a>
        
        <span class="text-uppercase fw-bold mt-4 mb-2 d-block px-2" style="font-size: 0.65rem; letter-spacing: 1px; color: #64748b;">Laporan</span>
        
        <a href="{{ route('assets.export') }}" class="nav-link-sidebar">
            <i class="bi bi-file-earmark-excel-fill fs-6" style="color: #34d399;"></i> Export Excel
        </a>
    </div>

    <!-- Bagian User Profile & Logout di Bawah Sidebar -->
    <div class="sidebar-user-section pt-3 px-3 border-top mt-auto" style="border-color: #1e293b !important;">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center gap-2" style="overflow: hidden;">
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold small text-white shadow-sm flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.75rem; background-color: #334155;">
                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                </div>
                <div style="overflow: hidden;">
                    <div class="fw-bold text-white text-truncate" style="font-size: 0.8rem; max-width: 110px;">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div style="font-size: 0.65rem; color: #64748b;">Administrator</div>
                </div>
            </div>
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm border text-danger p-1.5 shadow-sm logout-btn" title="Logout" style="border-radius: 6px; background-color: #334155; border-color: #475569 !important; transition: 0.2s;">
                    <i class="bi bi-box-arrow-right fs-6 text-danger"></i>
                </button>
            </form>
        </div>
        
        <!-- Footer Sidebar -->
        <div class="sidebar-footer">
            &copy; {{ date('Y') }} IT Division
        </div>
    </div>
</aside>

    <!-- Area Konten Utama -->
    <main class="main-wrapper">
        <!-- Topbar (Search / Profil) -->
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="mobile-toggle" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
                <div class="d-none d-md-block text-muted" style="font-size: 0.85rem;">
                    <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-weight: bold;">
                        AD
                    </div>
                    <span class="fw-semibold d-none d-sm-inline" style="font-size: 0.9rem;">Admin IT</span>
                </div>
            </div>
        </header>

        <!-- Konten Dinamis -->
        <div class="content-area">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Script Toggle Sidebar untuk Mobile -->
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
    </script>
    
    @if(session('success'))
        <script>
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', showConfirmButton: false, timer: 1200 });
        </script>
    @endif
</body>
</html>