@extends('assets.layout')
@section('content')

<style>
    .card-dashboard {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
    }

    .btn-light-custom { background-color: #ffffff; color: #475569; font-weight: 600; border-radius: 8px; font-size: 0.85rem; border: 1px solid #cbd5e1; transition: 0.3s; }
    .btn-light-custom:hover { background-color: #f8fafc; color: #0f172a; border-color: #94a3b8; }

    .badge-soft-green { background-color: #d1fae5; color: #059669; font-weight: 600; padding: 0.4em 0.7em; border-radius: 6px; font-size: 0.75rem; }
    .badge-soft-warning { background-color: #fef3c7; color: #d97706; font-weight: 600; padding: 0.4em 0.7em; border-radius: 6px; font-size: 0.75rem; }
    .badge-soft-danger { background-color: #fee2e2; color: #dc2626; font-weight: 600; padding: 0.4em 0.7em; border-radius: 6px; font-size: 0.75rem; }
    .badge-soft-blue { background-color: #e0e7ff; color: #4338ca; font-weight: 600; padding: 0.4em 0.7em; border-radius: 6px; font-size: 0.75rem; }

    .table-detail th { background-color: #f8fafc; color: #64748b; font-weight: 600; font-size: 0.85rem; border-bottom: 1px solid #f1f5f9; width: 35%; }
    .table-detail td { color: #334155; font-size: 0.9rem; border-bottom: 1px solid #f1f5f9; }

    /* --- CSS TIMELINE RIWAYAT --- */
    .timeline {
        position: relative;
        padding-left: 35px;
        margin-bottom: 0;
        list-style: none;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 14px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #e2e8f0;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    .timeline-icon {
        position: absolute;
        left: -35px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #fff;
        border: 2px solid #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
        box-shadow: 0 0 0 4px #ffffff;
    }
    .timeline-content {
        background-color: #f8fafc;
        border: 1px solid #f1f3f5;
        border-radius: 8px;
        padding: 15px;
        transition: 0.2s;
    }
    .timeline-content:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border-color: #e2e8f0;
    }
    .timeline-date {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 600;
        background-color: #e2e8f0;
        padding: 3px 8px;
        border-radius: 4px;
    }

    /* --- TWEAK RESPONSIVE UNTUK TABEL DETAIL (MOBILE VIEW) --- */
    @media (max-width: 767.98px) {
        .table-detail tr { 
            display: flex; 
            flex-direction: column; 
            border-bottom: 1px solid #f1f5f9; 
        }
        .table-detail th { 
            width: 100%; 
            border-bottom: none !important; 
            padding: 16px 16px 4px 16px !important; 
            background-color: transparent !important; 
        }
        .table-detail td { 
            width: 100%; 
            border-bottom: none !important; 
            padding: 0px 16px 16px 16px !important; 
        }
        .photo-cell {
            padding: 16px !important;
        }
    }
</style>

<div class="card-dashboard mx-auto p-4 p-md-5" style="max-width: 850px;"> 
    
    <!-- Header Halaman -->
    <div class="mb-4 pb-3 border-bottom text-center text-md-start">
        <h4 class="mb-1 fw-bold" style="color: #0f172a; letter-spacing: -0.5px;">Detail Aset IT</h4>
        <p class="text-muted small mb-0">Informasi lengkap spesifikasi dan riwayat perangkat.</p>
    </div>

    <!-- 1. Konten Detail -->
    <div class="border rounded-3 overflow-hidden mb-4" style="border-color: #e2e8f0 !important;">
        <!-- Header Kecil dalam Card -->
        <div class="px-4 py-3 border-bottom" style="background-color: #f8fafc;">
            <h6 class="mb-0 fw-bold" style="color: #4f46e5; font-size: 0.95rem;">
                <i class="bi bi-info-circle-fill me-2"></i>Data Registrasi: <span style="font-family: monospace;">{{ $asset->asset_code }}</span>
            </h6>
        </div>
        
        <div class="table-responsive">
            <table class="table table-detail mb-0 align-middle">
                <tbody>
                    <!-- BARIS FOTO -->
                    <tr>
                        <td colspan="2" class="text-center bg-white photo-cell py-4">
                            @if($asset->image)
                                <img src="{{ asset('storage/' . $asset->image) }}" alt="Foto Aset" class="rounded shadow-sm img-fluid" style="max-height: 280px; object-fit: cover; border: 1px solid #e2e8f0;">
                            @else
                                <div class="bg-light rounded-3 d-flex flex-column align-items-center justify-content-center mx-auto text-muted img-fluid" style="height: 200px; width: 100%; max-width: 350px; border: 2px dashed #cbd5e1;">
                                    <i class="bi bi-camera fs-1 mb-2" style="color: #94a3b8;"></i>
                                    <span class="small fw-semibold">Tidak Ada Foto Fisik</span>
                                </div>
                            @endif
                        </td>
                    </tr>
                    
                    <tr>
                        <th class="ps-md-4 py-3">Kode Aset (S/N)</th>
                        <td class="py-3 px-md-4">
                            <span class="fw-bold" style="color: #4f46e5; font-family: monospace; font-size: 1rem;">{{ $asset->asset_code }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-md-4 py-3">Nama / Merk Barang</th>
                        <td class="py-3 px-md-4">
                            <span class="fw-bold text-dark">{{ $asset->name }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-md-4 py-3">Kategori</th>
                        <td class="py-3 px-md-4">
                            <span class="badge-soft-blue">{{ $asset->category }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-md-4 py-3">Kondisi Saat Ini</th>
                        <td class="py-3 px-md-4">
                            @if($asset->condition == 'Baik')
                                <span class="badge-soft-green"><i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i> Baik</span>
                            @elseif($asset->condition == 'Rusak')
                                <span class="badge-soft-danger"><i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i> Rusak (Afkir)</span>
                            @else
                                <span class="badge-soft-warning"><i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i> Sedang Perbaikan</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-md-4 py-3">Catatan / Kendala</th>
                        <td class="py-3 px-md-4">
                            @if($asset->problem_description)
                                <span style="color: #475569; line-height: 1.5;">{{ $asset->problem_description }}</span>
                            @else
                                <span class="text-muted fst-italic">- Tidak ada kendala -</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-md-4 py-3">Status Pemakaian</th>
                        <td class="py-3 px-md-4">
                            @if($asset->assigned_to)
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2 border" style="width: 28px; height: 28px;">
                                        <i class="bi bi-person-fill text-muted" style="font-size: 0.8rem;"></i>
                                    </div>
                                    <span class="text-dark">Dipinjamkan kepada <strong class="fw-bold">{{ $asset->assigned_to }}</strong></span>
                                </div>
                            @else
                                <span class="fw-bold" style="color: #10b981;"><i class="bi bi-box-seam me-2"></i> Tersedia di Gudang IT</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-md-4 py-3">Tanggal Input Data</th>
                        <td class="py-3 px-md-4">
                            <i class="bi bi-calendar-plus me-1 text-muted"></i> {{ $asset->created_at->translatedFormat('d F Y - H:i') }} WIB
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-md-4 py-3 border-bottom-0">Terakhir Diupdate</th>
                        <td class="py-3 px-md-4 border-bottom-0">
                            <i class="bi bi-clock-history me-1 text-muted"></i> {{ $asset->updated_at->translatedFormat('d F Y - H:i') }} WIB
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. Konten Riwayat Mutasi & Pemeliharaan (Timeline) -->
    <div class="border rounded-3 overflow-hidden mb-4" style="border-color: #e2e8f0 !important;">
        <div class="px-4 py-3 border-bottom" style="background-color: #f8fafc;">
            <h6 class="mb-0 fw-bold" style="color: #1e293b; font-size: 0.95rem;">
                <i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Mutasi & Pemeliharaan
            </h6>
        </div>
        
        <div class="p-4 bg-white">
            @if($asset->histories && $asset->histories->count() > 0)
                <ul class="timeline">
                    @foreach($asset->histories as $history)
                        <li class="timeline-item">
                            <!-- Icon Dinamis berdasarkan tipe aksi -->
                            <div class="timeline-icon" style="color: #4f46e5; border-color: #4f46e5;">
                                @if($history->action == 'Registrasi Aset Baru')
                                    <i class="bi bi-plus-lg fs-6"></i>
                                @elseif($history->action == 'Mutasi Pemakai')
                                    <i class="bi bi-arrow-left-right fs-6"></i>
                                @elseif($history->action == 'Perubahan Kondisi')
                                    <i class="bi bi-wrench-adjustable fs-6 text-warning"></i>
                                @else
                                    <i class="bi bi-record-circle fs-6"></i>
                                @endif
                            </div>
                            
                            <!-- Konten Timeline -->
                            <div class="timeline-content">
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-2 gap-2">
                                    <span class="fw-bold" style="color: #1e293b; font-size: 0.95rem;">{{ $history->action }}</span>
                                    <span class="timeline-date"><i class="bi bi-calendar-event me-1"></i>{{ $history->created_at->translatedFormat('d M Y, H:i') }}</span>
                                </div>
                                <p class="text-muted small mb-2" style="line-height: 1.5;">{{ $history->notes }}</p>
                                <div class="d-flex align-items-center gap-1 text-muted fw-medium" style="font-size: 0.75rem;">
                                    <i class="bi bi-person-circle"></i> Oleh: 
                                    <span style="color: #4f46e5;">{{ $history->user->name ?? 'Sistem (Auto)' }}</span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-journal-x fs-1 mb-2 d-block" style="color: #cbd5e1;"></i>
                    <p class="small fw-medium mb-0">Belum ada riwayat tercatat untuk aset ini.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Tombol Kembali (W-100 di Mobile) -->
    <div class="d-flex justify-content-center justify-content-md-start">
        <a class="btn btn-light-custom px-4 shadow-sm w-100 w-md-auto d-flex justify-content-center align-items-center" href="{{ route('assets.index') }}">
            <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar
        </a>
    </div>
</div>
@endsection