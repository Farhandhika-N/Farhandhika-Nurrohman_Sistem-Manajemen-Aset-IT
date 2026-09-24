@extends('assets.layout')
@section('content')

<style>
    .card-dashboard {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
    }
    
    .btn-primary-custom { background-color: #4f46e5; color: white; font-weight: 600; border-radius: 8px; font-size: 0.85rem; border: none; transition: 0.3s; }
    .btn-primary-custom:hover { background-color: #4338ca; color: white; }
    
    .btn-pdf { 
        background-color: #ef4444; 
        color: white; 
        font-weight: 600; 
        border-radius: 8px; 
        font-size: 0.85rem; 
        padding: 0.375rem 1rem; 
        border: none; 
        transition: 0.3s; 
    }
    .btn-pdf:hover { 
        background-color: #dc2626; 
        color: white; 
    }

    .badge-soft-success { background-color: #d1fae5; color: #059669; font-weight: 600; padding: 0.4em 0.7em; border-radius: 6px; font-size: 0.75rem; }
    .badge-soft-primary { background-color: #e0e7ff; color: #4338ca; font-weight: 600; padding: 0.4em 0.7em; border-radius: 6px; font-size: 0.75rem; }
    .badge-soft-warning { background-color: #fef3c7; color: #d97706; font-weight: 600; padding: 0.4em 0.7em; border-radius: 6px; font-size: 0.75rem; }

    .table-custom th { 
        font-size: 0.75rem; 
        color: #64748b; 
        font-weight: 600; 
        text-transform: uppercase; 
        letter-spacing: 0.5px; 
        border-bottom: 2px solid #f1f5f9; 
        padding: 14px 18px; 
    }
    .table-custom td { 
        font-size: 0.85rem; 
        color: #334155; 
        vertical-align: middle; 
        border-bottom: 1px solid #f8fafc; 
        padding: 14px 18px; 
    }

    .col-notes {
        min-width: 260px;
        max-width: 350px;
        white-space: normal !important;
        word-break: break-word;
        padding-left: 20px !important;
        padding-right: 20px !important;
    }

    .input-ui { border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.85rem; color: #334155; transition: 0.2s; }
    .input-ui:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); outline: none; }

    .control-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 4px;
        white-space: nowrap;
    }

    .btn-reset-date-mini {
        background-color: #fee2e2;
        color: #dc2626;
        border: none;
        border-radius: 8px;
        width: 38px;
        min-width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
        cursor: pointer;
        padding: 0;
    }
    .btn-reset-date-mini:hover {
        background-color: #fecaca;
    }
</style>

<div class="card-dashboard p-4 mx-auto" style="max-width: 1200px;">
    
    <!-- Header Halaman -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 pb-3 border-bottom gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: #0f172a; letter-spacing: -0.5px; font-size: 1.25rem;">
                Log Mutasi & Aktivitas
            </h4>
            <p class="text-muted small mb-0">Rekam jejak seluruh aktivitas penambahan, pemeliharaan, dan mutasi aset.</p>
        </div>
        
        <!-- Tombol Cetak PDF Dinamis -->
        <div>
            <a id="btnPdfExport" href="{{ route('assets.history.pdf', request()->query()) }}" target="_blank" class="btn btn-pdf shadow-sm d-inline-flex align-items-center justify-content-center">
                <i class="bi bi-file-earmark-pdf-fill fs-6 me-2"></i> Preview & Cetak PDF
            </a>
        </div>
    </div>
    
    <!-- AREA KONTROL -->
    <div class="p-3 mb-4" style="background-color: #f8fafc; border-radius: 10px; border: 1px solid #f1f5f9;">
        <div class="row g-3 align-items-end">

            <!-- 1. Filter Jenis Aksi  -->
            <div class="col-12 col-md-6 col-lg-5 col-xl-4 d-flex flex-column flex-sm-row gap-2 align-items-sm-center">
                <span class="text-muted fw-bold small d-none d-sm-inline me-1" style="font-size: 0.75rem;"><i class="bi bi-funnel-fill me-1"></i> FILTER</span>
                <select id="filterAction" class="form-select input-ui py-2 flex-grow-1" style="cursor: pointer;">
                    <option value="">Semua Jenis Aksi</option>
                    <option value="Registrasi Aset Baru" {{ request('action_filter') == 'Registrasi Aset Baru' ? 'selected' : '' }}>Registrasi Aset Baru</option>
                    <option value="Mutasi Pemakai" {{ request('action_filter') == 'Mutasi Pemakai' ? 'selected' : '' }}>Mutasi Pemakai</option>
                    <option value="Perubahan Kondisi" {{ request('action_filter') == 'Perubahan Kondisi' ? 'selected' : '' }}>Perubahan Kondisi</option>
                    <option value="Perubahan Data" {{ request('action_filter') == 'Perubahan Data' ? 'selected' : '' }}>Perubahan Data</option>
                    <option value="Penghapusan Aset" {{ request('action_filter') == 'Penghapusan Aset' ? 'selected' : '' }}>Penghapusan Aset</option>
                </select>
            </div>

            <!-- 2. Rentang Waktu -->
            <div class="col-12 col-md-6 col-lg-7 col-xl-5">
                <span class="control-label">Rentang Waktu (Dari - Sampai)</span>
                <div class="d-flex align-items-center gap-1">
                    <input type="date" id="filterStartDate" class="form-control input-ui py-2 px-2 w-100" value="{{ request('start_date') }}" title="Dari Tanggal">
                    <span class="text-muted small px-1">-</span>
                    <input type="date" id="filterEndDate" class="form-control input-ui py-2 px-2 w-100" value="{{ request('end_date') }}" title="Sampai Tanggal">

                    <!-- Tombol Reset Waktu -->
                    <button type="button" id="clearDateBtn" class="btn-reset-date-mini ms-1 {{ (request('start_date') || request('end_date')) ? '' : 'd-none' }}" title="Hapus Filter Waktu">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            <!-- 3. Pencarian & Reset Global -->
            <div class="col-12 col-xl-3">
                <div class="d-flex gap-2 w-100">
                    <div class="input-group" style="border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; background: #fff;">
                        <span class="input-group-text bg-white border-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="search" id="filterSearch" class="form-control border-0 bg-white shadow-none py-2" placeholder="Cari data, catatan, admin..." value="{{ request('search') }}" autocomplete="off" style="font-size: 0.85rem;">
                    </div>

                    <!-- Tombol Reset Semua Filter -->
                    @if(request()->hasAny(['search', 'action_filter', 'start_date', 'end_date']) && (request('search') || request('action_filter') || request('start_date') || request('end_date')))
                        <a href="{{ route('assets.history') }}" class="btn btn-light border text-danger d-flex align-items-center justify-content-center input-ui flex-shrink-0 px-3" style="text-decoration: none;" title="Reset Semua Filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Container Tabel & Pagination yang di-update via AJAX -->
    <div id="tableContainer">
        <!-- Tabel Data -->
        <div class="table-responsive rounded-3 border mb-3" style="border-color: #e2e8f0 !important;">
            <table class="table table-custom table-hover mb-0 align-middle text-nowrap w-100">
                <thead>
                    <tr>
                        <th>Tanggal & Waktu</th>
                        <th>Aksi</th>
                        <th>Aset Terkait</th>
                        <th class="col-notes">Detail Catatan</th>
                        <th>Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($histories as $log)
                    <tr class="{{ $log->action == 'Penghapusan Aset' ? 'table-danger' : '' }}">
                        <td>
                            <span class="fw-bold text-dark">{{ $log->created_at->translatedFormat('d M Y') }}</span><br>
                            <span class="text-muted" style="font-size: 0.72rem;"><i class="bi bi-clock me-1"></i>{{ $log->created_at->format('H:i') }} WIB</span>
                        </td>
                        <td>
                            @if($log->action == 'Registrasi Aset Baru')
                                <span class="badge-soft-success"><i class="bi bi-plus-circle me-1"></i>{{ $log->action }}</span>
                            @elseif($log->action == 'Mutasi Pemakai')
                                <span class="badge-soft-primary"><i class="bi bi-arrow-left-right me-1"></i>{{ $log->action }}</span>
                            @elseif($log->action == 'Penghapusan Aset')
                                <span class="badge bg-danger text-white px-2 py-1 shadow-sm"><i class="bi bi-trash-fill me-1"></i>{{ $log->action }}</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1"><i class="bi bi-wrench me-1"></i>{{ $log->action }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-3 border-bottom-0">
                            @if($log->asset)
                                <a href="{{ route('assets.show', $log->asset_id) }}" class="fw-bold text-decoration-none" style="color: #4f46e5;">{{ $log->asset->asset_code }}</a><br>
                                <span class="text-muted text-truncate d-inline-block" style="max-width: 150px;">{{ $log->asset->name }}</span>
                            @else
                                <span class="fw-bold text-danger">Aset Terhapus</span><br>
                                <span class="text-danger small" style="font-style: italic;">(Data permanen dihapus)</span>
                            @endif
                        </td>
                        <td class="col-notes">
                            <span class="text-secondary" style="line-height: 1.4; display: inline-block;">{{ $log->notes }}</span>
                        </td>
                        <td class="text-dark fw-semibold">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-person-fill text-muted me-1"></i> {{ $log->user->name ?? 'Sistem' }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-journal-x fs-2 d-block mb-2 text-secondary"></i> 
                            Belum ada aktivitas yang tercatat atau sesuai dengan filter Anda.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer (Pagination) -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-2 gap-3">
            <span class="text-muted fw-semibold small text-center text-md-start" style="font-size: 0.85rem;">
                Menampilkan <span class="text-dark">{{ $histories->firstItem() ?? 0 }}</span> - <span class="text-dark">{{ $histories->lastItem() ?? 0 }}</span> dari <span class="text-dark">{{ $histories->total() }}</span> total riwayat
            </span>
            <div class="d-flex justify-content-center overflow-auto w-100 w-md-auto">
                {{ $histories->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

</div>

<!-- AJAX Live Filter & Script Tanggal Terpadu -->
<script>
    function fetchFilteredHistory(customUrl = null) {
        let search = document.getElementById('filterSearch').value;
        let action_filter = document.getElementById('filterAction').value;
        let start_date = document.getElementById('filterStartDate').value;
        let end_date = document.getElementById('filterEndDate').value;

        let clearBtn = document.getElementById('clearDateBtn');
        if (start_date || end_date) {
            clearBtn.classList.remove('d-none');
        } else {
            clearBtn.classList.add('d-none');
        }

        let url = customUrl ? new URL(customUrl) : new URL("{{ route('assets.history') }}");
        
        if (!customUrl) {
            if (search) url.searchParams.set('search', search);
            else url.searchParams.delete('search');

            if (action_filter) url.searchParams.set('action_filter', action_filter);
            else url.searchParams.delete('action_filter');

            if (start_date) url.searchParams.set('start_date', start_date);
            else url.searchParams.delete('start_date');

            if (end_date) url.searchParams.set('end_date', end_date);
            else url.searchParams.delete('end_date');
        }

        let pdfUrl = new URL("{{ route('assets.history.pdf') }}");
        let currentParams = url.searchParams;
        currentParams.forEach((value, key) => {
            pdfUrl.searchParams.set(key, value);
        });
        document.getElementById('btnPdfExport').href = pdfUrl.href;

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            let parser = new DOMParser();
            let doc = parser.parseFromString(html, 'text/html');
            document.getElementById('tableContainer').innerHTML = doc.getElementById('tableContainer').innerHTML;
            
            window.history.pushState({}, '', url);
            bindPaginationLinks();
        })
        .catch(error => console.log('Error fetching history:', error));
    }

    document.getElementById('clearDateBtn').addEventListener('click', function() {
        document.getElementById('filterStartDate').value = '';
        document.getElementById('filterEndDate').value = '';
        fetchFilteredHistory();
    });

    function bindPaginationLinks() {
        const paginationLinks = document.querySelectorAll('#tableContainer .pagination a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                let href = this.getAttribute('href');
                if (href) {
                    fetchFilteredHistory(href);
                }
            });
        });
    }

    document.getElementById('filterSearch').addEventListener('input', () => fetchFilteredHistory());
    document.getElementById('filterAction').addEventListener('change', () => fetchFilteredHistory());
    document.getElementById('filterStartDate').addEventListener('change', () => fetchFilteredHistory());
    document.getElementById('filterEndDate').addEventListener('change', () => fetchFilteredHistory());

    document.addEventListener("DOMContentLoaded", function() {
        bindPaginationLinks();
    });
</script>
@endsection