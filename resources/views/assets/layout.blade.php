<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise IT Asset Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body { 
            background-color: #f1f5f9 !important;
            color: #334155;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 0.9rem;
            overflow-x: hidden;
        }

        /* --- ANIMATION --- */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        main, .content-wrapper, .card-dashboard, .card-ui {
            animation: fadeInUp 0.35s ease-out forwards;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background-color: #0f172a;
            color: #94a3b8;
            transition: transform 0.3s ease-in-out;
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
        .nav-link-sidebar:hover { background-color: #1e293b; color: #ffffff; transform: translateX(4px); }
        .nav-link-sidebar.active { 
            background-color: #4f46e5; color: #ffffff; font-weight: 600; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35); 
        }
        
        .sidebar-footer { 
            padding: 16px 20px; 
            border-top: 1px solid #1e293b; 
            font-size: 0.8rem; 
            text-align: center; 
            color: #64748b;
        }

        /* --- MAIN CONTENT & HEADER --- */
        .main-wrapper {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease-in-out;
        }

        .top-navbar {
            height: 70px;
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 20px;
            justify-content: space-between;
        }

        .content-area { padding: 30px; flex-grow: 1; }

        .mobile-toggle { 
            background: none; 
            border: none; 
            font-size: 1.5rem; 
            color: #334155; 
            cursor: pointer; 
            padding: 5px 10px;
            border-radius: 6px;
            transition: background-color 0.2s;
        }
        .mobile-toggle:hover { background-color: #f1f5f9; }

        /* --- RESPONSIVE LOGIC (MOBILE & DESKTOP TOGGLE) --- */
        .sidebar.collapsed-desktop { transform: translateX(-260px); }
        .main-wrapper.expanded-desktop { margin-left: 0; }

        /* Kondisi Mobile Default (Sidebar tertutup) */
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-260px); }
            .main-wrapper { margin-left: 0; }
            /* Jika dibuka via JS, tambahkan class .show-mobile ke sidebar */
            .sidebar.show-mobile { transform: translateX(0); }
        }

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
<aside class="sidebar" id="sidebar">
    
<!-- Brand / Logo -->
    <div class="sidebar-brand" style="padding: 20px 16px;">
        <div class="d-flex align-items-center w-100 px-3 py-2 shadow-sm" style="background-color: #1e293b; border-radius: 8px; border: 1px solid #334155;">
            <i class="bi bi-layers-fill" style="color: #818cf8; font-size: 1.25rem;"></i>
            <div class="ms-3 d-flex flex-column" style="line-height: 1.1;">
                <span class="fw-bold text-white" style="font-size: 0.85rem; letter-spacing: 0.5px;">DATA ASSET</span>
                <span style="font-size: 0.65rem; color: #94a3b8; font-weight: 500; letter-spacing: 1px;">SYSTEM</span>
            </div>
        </div>
    </div>

    <!-- Navigasi Menu -->
    <div class="sidebar-nav">
        <span class="text-uppercase fw-bold mb-2 d-block px-2" style="font-size: 0.65rem; letter-spacing: 1px; color: #64748b;">Menu Utama</span>
        
        <a href="{{ route('assets.dashboard') }}" class="nav-link-sidebar {{ request()->routeIs('assets.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill fs-6"></i> Dashboard
        </a>
        
        <a href="{{ route('assets.index') }}" class="nav-link-sidebar {{ request()->routeIs('assets.index') || request()->routeIs('assets.show') || request()->routeIs('assets.edit') || request()->routeIs('assets.create') ? 'active' : '' }}">
            <i class="bi bi-hdd-network-fill fs-6"></i> Data Inventaris
        </a>
        
        <span class="text-uppercase fw-bold mt-4 mb-2 d-block px-2" style="font-size: 0.65rem; letter-spacing: 1px; color: #64748b;">Laporan</span>
        
        <a href="{{ route('assets.history') }}" class="nav-link-sidebar {{ request()->routeIs('assets.history') ? 'active' : '' }}">
            <i class="bi bi-clock-history fs-6" style="color: #f43f5e;"></i> Log Mutasi & Aktivitas
        </a>
        <a href="{{ route('assets.export') }}" class="nav-link-sidebar">
            <i class="bi bi-file-earmark-excel-fill fs-6" style="color: #34d399;"></i> Export Excel
        </a>
    </div>

    <!-- Bagian User Profile & Logout -->
    <div class="sidebar-user-section pt-3 px-3 border-top mt-auto" style="border-color: #1e293b !important;">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center gap-2" style="overflow: hidden;">
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold small text-white shadow-sm flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.75rem; background-color: #4f46e5;">
                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                </div>
                <div style="overflow: hidden;">
                    <div class="fw-bold text-white text-truncate" style="font-size: 0.8rem; max-width: 120px;">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div class="text-truncate" style="font-size: 0.65rem; color: #94a3b8; text-transform: capitalize;">
                        {{ auth()->user()->role ?? (auth()->user()->level ?? 'Administrator') }}
                    </div>
                </div>
            </div>
            
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="button" onclick="confirmLogout()" class="btn btn-sm text-white shadow-sm d-flex align-items-center justify-content-center flex-shrink-0" title="Keluar / Logout" style="border-radius: 6px; background-color: #dc2626; border: none; width: 32px; height: 32px; transition: 0.2s;">
                    <i class="bi bi-box-arrow-right fs-6"></i>
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
<main class="main-wrapper" id="main-wrapper">
    
    <!-- Topbar -->
    <header class="top-navbar shadow-sm">
        <div class="d-flex align-items-center gap-3">
            <button class="mobile-toggle" onclick="toggleSidebar()" title="Toggle Sidebar">
                <i class="bi bi-list"></i>
            </button>
            <span class="fw-semibold text-muted d-none d-sm-inline" style="font-size: 0.95rem;">IT Asset Management</span>
        </div>
        
        <div class="d-flex align-items-center gap-2 text-muted fw-medium" style="font-size: 0.85rem;">
            <i class="bi bi-calendar3 d-none d-sm-inline"></i> 
            {{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}
        </div>
    </header>

    <!-- Konten Dinamis -->
    <div class="content-area">
        @yield('content')
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script Toggle Sidebar & SweetAlert Logout -->
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('main-wrapper');
        
        if (window.innerWidth <= 991.98) {
            sidebar.classList.toggle('show-mobile');
        } else {
            sidebar.classList.toggle('collapsed-desktop');
            mainWrapper.classList.toggle('expanded-desktop');
        }
    }
    window.addEventListener('resize', function() {
        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('main-wrapper');
        
        if (window.innerWidth > 991.98) {
            sidebar.classList.remove('show-mobile');
        } else {
            sidebar.classList.remove('collapsed-desktop');
            mainWrapper.classList.remove('expanded-desktop');
        }
    });

    // --- SCRIPT SWEETALERT UNTUK LOGOUT ---
    function confirmLogout() {
        Swal.fire({
            title: 'Konfirmasi Keluar',
            text: "Apakah Anda yakin ingin keluar dari sistem?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#e11e1e', 
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>

@if(session('success'))
    <script>
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', showConfirmButton: false, timer: 1500 });
    </script>
@endif

</body>
</html>