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

    .input-ui { border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; color: #334155; transition: 0.2s; padding: 0.6rem 0.75rem; }
    .input-ui:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); outline: none; }

    .form-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 0.4rem; }

    .badge-soft-indigo { background-color: #e0e7ff; color: #4338ca; font-weight: 600; padding: 0.4em 0.7em; border-radius: 6px; font-size: 0.75rem; }
    .badge-soft-slate { background-color: #f1f5f9; color: #475569; font-weight: 600; padding: 0.4em 0.7em; border-radius: 6px; font-size: 0.75rem; }
</style>

<div class="mb-4">
    <h4 class="fw-bold mb-1" style="color: #0f172a; letter-spacing: -0.5px;">Profil Saya</h4>
    <p class="text-muted small mb-0">Perbarui informasi akun dan password login Anda.</p>
</div>

<div class="row g-4">

    <!-- Kartu: Informasi Profil -->
    <div class="col-lg-6">
        <div class="card-dashboard p-4 h-100">
            <!-- Ringkasan Akun -->
            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white shadow-sm flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.25rem; background-color: #4f46e5;">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div style="line-height: 1.3;">
                    <div class="fw-bold text-dark">{{ $user->name }}</div>
                    <span class="{{ $user->role === 'admin' ? 'badge-soft-indigo' : 'badge-soft-slate' }}">
                        <i class="bi {{ $user->role === 'admin' ? 'bi-shield-check' : 'bi-person' }} me-1"></i>
                        {{ $user->role === 'admin' ? 'Administrator' : 'Staff' }}
                    </span>
                </div>
            </div>

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control input-ui @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                    @error('name')
                        <div class="invalid-feedback fw-semibold small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control input-ui @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
                    @error('email')
                        <div class="invalid-feedback fw-semibold small">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary-custom px-4 shadow-sm d-flex align-items-center">
                    <i class="bi bi-check-lg me-2"></i> Simpan Profil
                </button>
            </form>
        </div>
    </div>

    <!-- Kartu: Ganti Password -->
    <div class="col-lg-6">
        <div class="card-dashboard p-4 h-100">
            <div class="mb-4 pb-3 border-bottom">
                <h6 class="fw-bold mb-1 text-dark"><i class="bi bi-key me-1"></i> Ganti Password</h6>
                <p class="text-muted small mb-0">Password lama wajib dikonfirmasi untuk keamanan akun.</p>
            </div>

            <form action="{{ route('profile.password') }}" method="POST">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold">Password Lama <span class="text-danger">*</span></label>
                    <input type="password" name="current_password" class="form-control input-ui @error('current_password') is-invalid @enderror" placeholder="Masukkan password Anda saat ini">
                    @error('current_password')
                        <div class="invalid-feedback fw-semibold small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Password Baru <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control input-ui @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter">
                    @error('password')
                        <div class="invalid-feedback fw-semibold small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control input-ui" placeholder="Ulangi password baru">
                </div>

                <button type="submit" class="btn btn-primary-custom px-4 shadow-sm d-flex align-items-center">
                    <i class="bi bi-shield-lock me-2"></i> Ganti Password
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
