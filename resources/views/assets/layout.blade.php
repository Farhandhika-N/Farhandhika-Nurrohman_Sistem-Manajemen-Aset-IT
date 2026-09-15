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

        /* --- SIDEBAR ENTERPRISE --- */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background-color: #0f172a; /* Biru sangat gelap / Slate */
            color: #94a3b8;
            transition: all 0.3s ease-in-out;
            z-index: 1040;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-brand {
            padding: 24px 20px;
            font-size: 1.25rem;
            font-weight: 700;
            color: #ffffff;
            border-bottom: 1px solid #1e293b;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-nav {
            padding: 20px 10px;
            flex-grow: 1;
        }

        .nav-link-sidebar {
            color: #94a3b8;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }

        .nav-link-sidebar:hover { background-color: #1e293b; color: #ffffff; }
        .nav-link-sidebar.active { background-color: #3b82f6; color: #ffffff; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);}
        
        .sidebar-footer { padding: 20px; border-top: 1px solid #1e293b; font-size: 0.8rem; text-align: center; }

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
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-box-seam text-primary"></i>
            <span>IT Asset Management</span>
        </div>
        <div class="sidebar-nav">
            <span class="text-uppercase fw-bold mb-2 d-block ms-3" style="font-size: 0.7rem; letter-spacing: 1px; color: #475569;">Menu Utama</span>
            
            <a href="{{ route('dashboard') }}" class="nav-link-sidebar {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill fs-6"></i> Dashboard
            </a>
            <a href="{{ route('assets.index') }}" class="nav-link-sidebar {{ request()->routeIs('assets.index') || request()->routeIs('assets.show') || request()->routeIs('assets.edit') ? 'active' : '' }}">
                <i class="bi bi-hdd-network-fill fs-6"></i> Data Inventaris
            </a>
            
            <span class="text-uppercase fw-bold mt-4 mb-2 d-block ms-3" style="font-size: 0.7rem; letter-spacing: 1px; color: #475569;">Laporan</span>
            <a href="{{ route('assets.export') }}" class="nav-link-sidebar">
                <i class="bi bi-file-earmark-excel-fill fs-6"></i> Export Excel
            </a>
        </div>
        <div class="sidebar-footer">
            &copy; 2026 IT Division
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