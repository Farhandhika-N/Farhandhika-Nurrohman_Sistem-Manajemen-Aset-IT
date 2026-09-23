@extends('assets.layout')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center align-items-center min-vh-75 py-5">
        <div class="col-lg-6 col-md-8">
            <div class="card-ui text-center p-4 p-md-5" style="animation: fadeInUp 0.35s ease-out forwards;">
                <div class="mx-auto mb-4 d-flex align-items-center justify-content-center shadow-sm"
                     style="width: 80px; height: 80px; border-radius: 50%; background-color: #fef2f2; color: #ef4444;">
                    <i class="bi bi-shield-lock-fill" style="font-size: 2.25rem;"></i>
                </div>

                <h1 class="fw-bold mb-2" style="color: #0f172a; font-size: 2.5rem; letter-spacing: -1px;">403</h1>
                <h2 class="fw-bold mb-3" style="color: #334155; font-size: 1.15rem;">Akses Ditolak</h2>

                <p class="text-muted mb-4" style="font-size: 0.9rem;">
                    Maaf, Anda tidak memiliki izin untuk membuka halaman ini.
                    Fitur tambah dan hapus aset hanya dapat diakses oleh akun
                    <span class="fw-bold text-dark">Administrator</span>.
                </p>

                <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
                    <a href="{{ route('assets.dashboard') }}" class="btn-action btn-action-primary px-4">
                        <i class="bi bi-grid-1x2-fill me-2"></i> Kembali ke Dashboard
                    </a>
                    <a href="{{ route('assets.index') }}" class="btn-action px-4" style="background-color: #64748b;">
                        <i class="bi bi-hdd-network-fill me-2"></i> Lihat Data Inventaris
                    </a>
                </div>
            </div>

            <p class="text-center text-muted small mt-3 mb-0">
                <i class="bi bi-info-circle me-1"></i>
                Jika Anda merasa ini keliru, hubungi administrator sistem.
            </p>
        </div>
    </div>
</div>
@endsection
