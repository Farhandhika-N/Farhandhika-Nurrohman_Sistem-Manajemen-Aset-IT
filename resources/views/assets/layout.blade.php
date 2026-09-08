<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Aset IT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #f8f9fa;
            color: #495057;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 0.9rem;
        }
        
        /* Responsif Padding */
        .card-ui {
            background-color: #ffffff;
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.04);
            padding: 16px; 
        }
        @media (min-width: 768px) {
            .card-ui { padding: 25px 30px; } 
        }

        /* Input & Select UI */
        .input-ui {
            background-color: #f8f9fa;
            border: 1px solid #f1f3f5;
            border-radius: 6px;
            padding: 8px 14px;
            color: #495057;
            font-size: 0.85rem;
            transition: all 0.2s;
            width: 100%; 
        }
        .input-ui:focus {
            background-color: #ffffff;
            border-color: #dae0e5;
            outline: none;
            box-shadow: 0 0 0 3px rgba(218, 224, 229, 0.3);
        }
        
        @media (min-width: 576px) {
            .filter-input { width: auto; min-width: 150px; }
            .search-input { width: 250px; }
        }

        /* Tabel UI */
        .table-ui { width: 100%; margin-bottom: 1rem; border-collapse: separate; border-spacing: 0; }
        .table-ui thead th {
            background-color: #f8f9fa;
            color: #343a40;
            font-weight: 600;
            padding: 12px 15px;
            border: none;
            white-space: nowrap;
        }
        .table-ui thead th:first-child { border-top-left-radius: 6px; border-bottom-left-radius: 6px; }
        .table-ui thead th:last-child { border-top-right-radius: 6px; border-bottom-right-radius: 6px; }
        
        .table-ui tbody td {
            padding: 14px 15px;
            border-bottom: 1px solid #f1f3f5;
            vertical-align: middle;
        }
        
        .action-buttons { white-space: nowrap; } 

        /* Warna Tombol */
        .btn-action {
            border: none; border-radius: 6px; padding: 6px 14px;
            font-weight: 500; font-size: 0.85rem; color: #fff;
            text-decoration: none; display: inline-block;
            text-align: center; transition: background-color 0.2s;
        }
        .btn-action-primary { background-color: #3b82f6; } /* Biru Utama */
        .btn-action-primary:hover { background-color: #2563eb; color: #fff; }
        
        .btn-action-info { background-color: #06b6d4; } /* Cyan untuk Detail */
        .btn-action-info:hover { background-color: #0891b2; color: #fff; }
        
        .btn-action-warning { background-color: #f59e0b; } /* Kuning/Amber untuk Edit */
        .btn-action-warning:hover { background-color: #d97706; color: #fff; }
        
        .btn-action-danger { background-color: #f43f5e; } /* Merah/Rose untuk Hapus */
        .btn-action-danger:hover { background-color: #e11d48; color: #fff; }

        /* Kustomisasi Pagination */
        .pagination { justify-content: center; margin-top: 10px; flex-wrap: wrap; }
        .pagination .page-item .page-link {
            border: none; color: #6c757d; background: transparent;
            margin: 2px 3px; border-radius: 6px; font-weight: 500;
        }
        .pagination .page-item.active .page-link { background-color: #3b82f6; color: white; }
    </style>
</head>
<body>
    <div class="container mt-4 mt-md-5 mb-5">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
        <script>
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', showConfirmButton: false, timer: 1200 });
        </script>
    @endif
</body>
</html>