@extends('assets.layout')
@section('content')
<div class="card-ui mx-auto" style="max-width: 800px;">
    <!-- Judul Form -->
    <div class="mb-4 text-center text-md-start">
        <h4 class="mb-0 fw-bold" style="color: #2c3e50;">Tambah Aset IT Baru</h4>
        <p class="text-muted small mt-1">Lengkapi form di bawah ini untuk mendaftarkan aset ke dalam sistem.</p>
    </div>

    <!-- Menampilkan Error Validasi (Jika ada input yang salah/kosong/duplikat) -->
    @if ($errors->any())
        <div class="alert alert-danger" style="border-radius: 8px;">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('assets.store') }}" method="POST">
        @csrf 
        
        <!-- Grid Form Input -->
        <div class="row g-3 mb-4">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
                <label class="form-label fw-semibold text-muted small mb-1">Kode Aset (S/N) <span class="text-danger">*</span></label>
                <input type="text" name="asset_code" class="form-control input-ui" placeholder="Misal: LPT-001" value="{{ old('asset_code') }}" required>
            </div>
            
            <!-- Kolom Kanan -->
            <div class="col-md-6">
                <label class="form-label fw-semibold text-muted small mb-1">Nama / Merk Barang <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control input-ui" placeholder="Misal: Lenovo ThinkPad T490" value="{{ old('name') }}" required>
            </div>
            
            <!-- Kolom Kiri -->
            <div class="col-md-6">
                <label class="form-label fw-semibold text-muted small mb-1">Kategori <span class="text-danger">*</span></label>
                <select name="category" class="form-select input-ui" required>
                    <option value="" disabled {{ old('category') == '' ? 'selected' : '' }}>-- Pilih Kategori --</option>
                    <option value="Laptop" {{ old('category') == 'Laptop' ? 'selected' : '' }}>Laptop</option>
                    <option value="PC Desktop" {{ old('category') == 'PC Desktop' ? 'selected' : '' }}>PC Desktop</option>
                    <option value="Printer" {{ old('category') == 'Printer' ? 'selected' : '' }}>Printer</option>
                    <option value="Router" {{ old('category') == 'Router' ? 'selected' : '' }}>Router</option>
                </select>
            </div>
            
            <!-- Kolom Kanan -->
            <div class="col-md-6">
                <label class="form-label fw-semibold text-muted small mb-1">Kondisi <span class="text-danger">*</span></label>
                <select name="condition" class="form-select input-ui" required>
                    <option value="Baik" {{ old('condition') == 'Baik' ? 'selected' : '' }}>Baik</option>
                    <option value="Perbaikan" {{ old('condition') == 'Perbaikan' ? 'selected' : '' }}>Sedang Perbaikan</option>
                    <option value="Rusak" {{ old('condition') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                </select>
            </div>
            
            <!-- Kolom Penuh (Bawah) -->
            <div class="col-12">
                <label class="form-label fw-semibold text-muted small mb-1">Dipinjamkan Kepada <span class="text-secondary fw-normal">(Opsional)</span></label>
                <input type="text" name="assigned_to" class="form-control input-ui" placeholder="Kosongkan jika tersedia di Gudang IT..." value="{{ old('assigned_to') }}">
            </div>
        </div>

        <hr style="border-color: #e9ecef;" class="mb-4">

        <!-- Tombol Aksi -->
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('assets.index') }}" class="btn btn-light border text-muted px-4" style="border-radius: 6px;">
                Batal
            </a>
            <button type="submit" class="btn btn-action btn-action-primary px-4">
                Simpan Data
            </button>
        </div>
    </form>
</div>
@endsection