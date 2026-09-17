@extends('assets.layout')
@section('content')

<style>
    /* Menyamakan style dasar dengan Dashboard & Index Aset */
    .card-dashboard {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
    }
    
    .btn-primary-custom { background-color: #4f46e5; color: white; font-weight: 600; border-radius: 8px; font-size: 0.85rem; border: none; transition: 0.3s; }
    .btn-primary-custom:hover { background-color: #4338ca; color: white; }
    
    /* Tombol PDF yang lebih proporsional, tidak terlalu besar, dan berwarna (tidak putih polos) */
    .btn-pdf { 
        background-color: #ef4444; 
        color: white; 
        font-weight: 600; 
        border-radius: 8px; 
        font-size: 0.8rem; 
        padding: 0.45rem 1rem; 
        border: none; 
        transition: 0.3s; 
    }
    .btn-pdf:hover { 
        background-color: #dc2626; 
        color: white; 
        transform: translateY(-1px);
    }

    .badge-soft-success { background-color: #d1fae5; color: #059669; font-weight: 600; padding: 0.4em 0.7em; border-radius: 6px; font-size: 0.75rem; }
    .badge-soft-primary { background-color: #e0e7ff; color: #4338ca; font-weight: 600; padding: 0.4em 0.7em; border-radius: 6px; font-size: 0.75rem; }
    .badge-soft-warning { background-color: #fef3c7; color: #d97706; font-weight: 600; padding: 0.4em 0.7em; border-radius: 6px; font-size: 0.75rem; }

    /* Padding tabel dan kolom catatan yang lebih lega */
    .table-custom th { 
        font-size: 0.75rem; 
        color: #64748b; 
        font-weight: 600; 
        text-transform: uppercase; 
        letter-spacing: 0.5px; 
        border-bottom: 2px solid #f1f5f9; 
        padding: 16px 20px; 
    }
    .table-custom td { 
        font-size: 0.85rem; 
        color: #334155; 
        vertical-align: middle; 
        border-bottom: 1px solid #f8fafc; 
        padding: 16px 20px; 
    }

    /* Spesifik untuk kolom catatan agar ada ruang lebih & tidak mepet */
    .col-notes {
        min-width: 280px;
        max-width: 380px;
        white-space: normal !important;
        word-break: break-word;
        padding-left: 24px !important;
        padding-right: 24px !important;
    }
</style>

<div class="card-dashboard p-4 p-md-5 mx-auto" style="max-width: 1150px;">
    
    <!-- Header Halaman -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 pb-3 border-bottom gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: #0f172a; letter-spacing: -0.5px;">
                Log Mutasi & Aktivitas
            </h4>
            <p class="text-muted small mb-0">Rekam jejak seluruh aktivitas penambahan, pemeliharaan, dan mutasi aset.</p>
        </div>
        
        <!-- Tombol Cetak PDF yang Proporsional -->
        <div>
            <a href="{{ route('assets.history.pdf') }}" target="_blank" class="btn btn-pdf shadow-sm d-inline-flex align-items-center justify-content-center">
                <i class="bi bi-file-earmark-pdf-fill fs-6 me-1.5"></i> Preview & Cetak PDF
            </a>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="table-responsive rounded-3 border mb-4" style="border-color: #e2e8f0 !important;">
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
                <tr>
                    <td>
                        <span class="fw-bold text-dark">{{ $log->created_at->translatedFormat('d M Y') }}</span><br>
                        <span class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i>{{ $log->created_at->format('H:i') }} WIB</span>
                    </td>
                    <td>
                        @if($log->action == 'Registrasi Aset Baru')
                            <span class="badge-soft-success"><i class="bi bi-plus-circle me-1"></i>{{ $log->action }}</span>
                        @elseif($log->action == 'Mutasi Pemakai')
                            <span class="badge-soft-primary"><i class="bi bi-arrow-left-right me-1"></i>{{ $log->action }}</span>
                        @else
                            <span class="badge-soft-warning"><i class="bi bi-wrench me-1"></i>{{ $log->action }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('assets.show', $log->asset_id) }}" class="fw-bold text-decoration-none" style="color: #4f46e5; font-family: monospace;">{{ $log->asset->asset_code ?? 'Aset Terhapus' }}</a><br>
                        <span class="text-muted text-truncate d-inline-block" style="max-width: 180px;">{{ $log->asset->name ?? '-' }}</span>
                    </td>
                    <!-- Kolom Catatan yang Diperlebar & Diberi Ruang Nyaman -->
                    <td class="col-notes">
                        <span class="text-secondary" style="line-height: 1.5; display: inline-block;">{{ $log->notes }}</span>
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
                        Belum ada aktivitas yang tercatat dalam sistem.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer (Pagination) -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-3 border-top gap-3">
        <span class="text-muted fw-semibold small text-center text-md-start" style="font-size: 0.85rem;">
            Menampilkan <span class="text-dark">{{ $histories->firstItem() ?? 0 }}</span> - <span class="text-dark">{{ $histories->lastItem() ?? 0 }}</span> dari <span class="text-dark">{{ $histories->total() }}</span> total riwayat
        </span>
        <div class="d-flex justify-content-center overflow-auto w-100 w-md-auto">
            {{ $histories->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>

</div>
@endsection