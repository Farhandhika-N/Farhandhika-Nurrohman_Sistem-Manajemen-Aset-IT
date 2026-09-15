@extends('assets.layout')
@section('content')

<style>
    /* Menyamakan style dasar dengan Dashboard */
    .card-dashboard {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
    }
    
    .btn-primary-custom { background-color: #4f46e5; color: white; font-weight: 600; border-radius: 8px; font-size: 0.85rem; border: none; transition: 0.3s; }
    .btn-primary-custom:hover { background-color: #4338ca; color: white; }
    
    .btn-export { background-color: #ffffff; color: #10b981; font-weight: 600; border-radius: 8px; font-size: 0.85rem; border: 1px solid #10b981; transition: 0.3s; }
    .btn-export:hover { background-color: #ecfdf5; color: #10b981; }

    .badge-soft-green { background-color: #d1fae5; color: #059669; font-weight: 600; padding: 0.4em 0.7em; border-radius: 6px; font-size: 0.75rem; }
    .badge-soft-warning { background-color: #fef3c7; color: #d97706; font-weight: 600; padding: 0.4em 0.7em; border-radius: 6px; font-size: 0.75rem; }
    .badge-soft-danger { background-color: #fee2e2; color: #dc2626; font-weight: 600; padding: 0.4em 0.7em; border-radius: 6px; font-size: 0.75rem; }

    /* PERBAIKAN PADDING TABEL AGAR LEBIH LEGA */
    .table-custom th { 
        font-size: 0.75rem; /* Sedikit diperbesar dari 0.7rem */
        color: #64748b; 
        font-weight: 600; 
        text-transform: uppercase; 
        letter-spacing: 0.5px; 
        border-bottom: 2px solid #f1f5f9; 
        padding: 16px 20px; /* Jarak atas-bawah 16px, kiri-kanan 20px */
    }
    .table-custom td { 
        font-size: 0.85rem; 
        color: #334155; 
        vertical-align: middle; 
        border-bottom: 1px solid #f8fafc; 
        padding: 16px 20px; /* Menyamakan dengan header agar presisi */
    }

    .input-ui { border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.85rem; color: #334155; transition: 0.2s; }
    .input-ui:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); outline: none; }
    
    .action-btn { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; transition: 0.2s; border: none; }
    .action-btn:hover { transform: translateY(-2px); }
    .action-btn-info { background: #e0f2fe; color: #0284c7; }
    .action-btn-warning { background: #fef3c7; color: #d97706; }
    .action-btn-danger { background: #fee2e2; color: #dc2626; }
</style>

<div class="card-dashboard p-4">
    
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 pb-3 border-bottom gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: #0f172a; letter-spacing: -0.5px;">Data Inventaris Aset IT</h4>
            <p class="text-muted small mb-0">Kelola, filter, dan pantau seluruh aset perangkat keras perusahaan.</p>
        </div>
        
        <!-- Tombol Aksi -->
        <div class="d-flex flex-column flex-sm-row gap-2 ms-md-auto w-100 w-md-auto justify-content-md-end">
            <a id="btnExport" class="btn btn-export shadow-sm d-flex align-items-center justify-content-center px-3" href="{{ route('assets.export') }}">
                <i class="bi bi-file-earmark-excel fs-6 me-2"></i> Export Excel
            </a>
            <a class="btn btn-primary-custom shadow-sm d-flex align-items-center justify-content-center px-4" href="{{ route('assets.create') }}">
                <i class="bi bi-plus-lg fs-6 me-2"></i> Tambah Aset
            </a>
        </div>
    </div>
    
    <!-- Area Kontrol (Filter & Search) -->
    <div class="p-3 mb-4" style="background-color: #f8fafc; border-radius: 10px; border: 1px solid #f1f5f9;">
        <div class="row g-3 align-items-center">
            
            <!-- Filter Area -->
            <div class="col-12 col-lg-8 d-flex flex-column flex-sm-row gap-2 align-items-sm-center">
                <span class="text-muted fw-bold small d-none d-sm-inline me-1" style="font-size: 0.75rem;"><i class="bi bi-funnel-fill me-1"></i> FILTER</span>
                
                <select id="filterCategory" class="form-select input-ui py-2 flex-grow-1" style="cursor: pointer;">
                    <option value="">Semua Kategori</option>
                    <option value="Laptop" {{ request('category') == 'Laptop' ? 'selected' : '' }}>Laptop</option>
                    <option value="PC Desktop" {{ request('category') == 'PC Desktop' ? 'selected' : '' }}>PC Desktop</option>
                    <option value="Printer" {{ request('category') == 'Printer' ? 'selected' : '' }}>Printer</option>
                    <option value="Router" {{ request('category') == 'Router' ? 'selected' : '' }}>Router</option>
                </select>
                
                <select id="filterCondition" class="form-select input-ui py-2 flex-grow-1" style="cursor: pointer;">
                    <option value="">Semua Kondisi</option>
                    <option value="Baik" {{ request('condition') == 'Baik' ? 'selected' : '' }}>Baik</option>
                    <option value="Perbaikan" {{ request('condition') == 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                    <option value="Rusak" {{ request('condition') == 'Rusak' ? 'selected' : '' }}>Rusak (Afkir)</option>
                </select>
            </div>

            <!-- Pencarian Area -->
            <div class="col-12 col-lg-4">
                <div class="input-group" style="border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; background: #fff;">
                    <span class="input-group-text bg-white border-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="search" id="filterSearch" class="form-control border-0 bg-white shadow-none py-2" placeholder="Cari nama, SN, pemakai..." value="{{ request('search') }}" autocomplete="off" style="font-size: 0.85rem;">
                </div>
            </div>

        </div>
    </div>

    <!-- Tabel Data -->
    <div id="tableContainer">
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle text-nowrap mb-0 w-100">
                <thead>
                    <tr>
                        <th>Kode / S/N</th>
                        <th>Item Barang</th>
                        <th>Kategori</th>
                        <th>Kondisi</th>
                        <th>Status / Pemakai</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assets as $asset)
                    <tr>
                        <td class="fw-bold" style="color: #4f46e5; font-family: monospace;">{{ $asset->asset_code }}</td>
                        <td class="fw-bold text-dark">{{ $asset->name }}</td>
                        <td>{{ $asset->category }}</td>
                        <td>
                            @if($asset->condition == 'Baik')
                                <span class="badge-soft-green"><i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i> Baik</span>
                            @elseif($asset->condition == 'Rusak')
                                <span class="badge-soft-danger"><i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i> Rusak</span>
                            @else
                                <span class="badge-soft-warning"><i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i> Perbaikan</span>
                            @endif
                        </td>
                        <td>
                            @if($asset->assigned_to)
                                <span class="text-dark fw-semibold"><i class="bi bi-person me-1 text-muted"></i> {{ $asset->assigned_to }}</span>
                            @else
                                <span class="text-muted small fst-italic"><i class="bi bi-box me-1"></i> Gudang IT</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <form id="delete-form-{{ $asset->id }}" action="{{ route('assets.destroy', $asset->id) }}" method="POST" class="m-0 d-inline-flex gap-2 justify-content-center">
                                <a class="action-btn action-btn-info" href="{{ route('assets.show', $asset->id) }}" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a class="action-btn action-btn-warning" href="{{ route('assets.edit', $asset->id) }}" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                @csrf @method('DELETE')
                                <button type="button" class="action-btn action-btn-danger" onclick="confirmDelete({{ $asset->id }})" title="Hapus">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2" style="color: #cbd5e1;"></i>
                            Data aset tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer (Pagination) -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-4 border-top gap-3">
            <span class="text-muted fw-semibold small text-center text-md-start" style="font-size: 0.85rem;">
                Menampilkan <span class="text-dark">{{ $assets->firstItem() ?? 0 }}</span> - <span class="text-dark">{{ $assets->lastItem() ?? 0 }}</span> dari <span class="text-dark">{{ $assets->total() }}</span> total aset
            </span>
            <div class="d-flex justify-content-center overflow-auto w-100 w-md-auto">
                {{ $assets->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- Script -->
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: "Data aset IT ini akan dihapus secara permanen dari sistem!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', 
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus Data',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    function fetchFilteredData() {
        let search = document.getElementById('filterSearch').value;
        let category = document.getElementById('filterCategory').value;
        let condition = document.getElementById('filterCondition').value;

        let url = new URL("{{ route('assets.index') }}");
        if (search) url.searchParams.append('search', search);
        if (category) url.searchParams.append('category', category);
        if (condition) url.searchParams.append('condition', condition);

        let exportUrl = new URL("{{ route('assets.export') }}");
        if (search) exportUrl.searchParams.append('search', search);
        if (category) exportUrl.searchParams.append('category', category);
        if (condition) exportUrl.searchParams.append('condition', condition);
        document.getElementById('btnExport').href = exportUrl.href;

        fetch(url)
            .then(response => response.text())
            .then(html => {
                let parser = new DOMParser();
                let doc = parser.parseFromString(html, 'text/html');
                document.getElementById('tableContainer').innerHTML = doc.getElementById('tableContainer').innerHTML;
            })
            .catch(error => console.log('Error fetching data:', error));
    }

    document.getElementById('filterSearch').addEventListener('input', fetchFilteredData);
    document.getElementById('filterCategory').addEventListener('change', fetchFilteredData);
    document.getElementById('filterCondition').addEventListener('change', fetchFilteredData);
</script>
@endsection