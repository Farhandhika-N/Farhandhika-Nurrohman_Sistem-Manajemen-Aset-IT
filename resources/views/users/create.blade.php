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

    .btn-light-custom { background-color: #ffffff; color: #475569; font-weight: 600; border-radius: 8px; font-size: 0.85rem; border: 1px solid #cbd5e1; transition: 0.3s; }
    .btn-light-custom:hover { background-color: #f8fafc; color: #0f172a; border-color: #94a3b8; }

    .input-ui { border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; color: #334155; transition: 0.2s; padding: 0.6rem 0.75rem; }
    .input-ui:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); outline: none; }

    .form-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 0.4rem; }
</style>

<div class="card-dashboard mx-auto p-4 p-md-5" style="max-width: 800px;">

    <!-- Judul Form -->
    <div class="mb-4 pb-3 border-bottom text-center text-md-start">
        <h4 class="mb-1 fw-bold" style="color: #0f172a; letter-spacing: -0.5px;">Tambah User Baru</h4>
        <p class="text-muted small mb-0">Buat akun pengguna baru dan tentukan role (hak akses)nya.</p>
    </div>

    <!-- Form Create -->
    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="row g-4 mb-4">

            <!-- Kolom Kiri: Nama -->
            <div class="col-md-6">
                <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control input-ui @error('name') is-invalid @enderror" placeholder="Misal: Budi Santoso" value="{{ old('name') }}">
                @error('name')
                    <div class="invalid-feedback fw-semibold small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Kolom Kanan: Email -->
            <div class="col-md-6">
                <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control input-ui @error('email') is-invalid @enderror" placeholder="Misal: budi@perusahaan.com" value="{{ old('email') }}">
                @error('email')
                    <div class="invalid-feedback fw-semibold small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Kolom Kiri: Role -->
            <div class="col-md-6">
                <label class="form-label fw-bold">Role / Hak Akses <span class="text-danger">*</span></label>
                <select name="role" class="form-select input-ui @error('role') is-invalid @enderror" style="cursor: pointer;">
                    <option value="" disabled {{ old('role') == '' ? 'selected' : '' }}>-- Pilih Role --</option>
                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff (edit & lihat data saja)</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator (akses penuh)</option>
                </select>
                @error('role')
                    <div class="invalid-feedback fw-semibold small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Kolom Kanan: Password -->
            <div class="col-md-6">
                <label class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control input-ui @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter">
                @error('password')
                    <div class="invalid-feedback fw-semibold small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Full Width: Konfirmasi Password -->
            <div class="col-12">
                <label class="form-label fw-bold">Konfirmasi Password <span class="text-danger">*</span></label>
                <input type="password" name="password_confirmation" class="form-control input-ui" placeholder="Ulangi password di atas">
            </div>

        </div>

        <!-- Tombol Aksi -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mt-4 pt-4 border-top gap-3">
            <a href="{{ route('users.index') }}" class="btn btn-light-custom px-4 shadow-sm w-100 w-sm-auto text-center">
                Batal
            </a>
            <button type="submit" class="btn btn-primary-custom px-4 shadow-sm w-100 w-sm-auto d-flex justify-content-center align-items-center">
                <i class="bi bi-save me-2"></i> Simpan User
            </button>
        </div>
    </form>
</div>
@endsection
