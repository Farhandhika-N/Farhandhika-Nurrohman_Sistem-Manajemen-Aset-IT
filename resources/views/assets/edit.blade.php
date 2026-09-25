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
        <h4 class="mb-1 fw-bold" style="color: #0f172a; letter-spacing: -0.5px;">Edit Data Aset IT</h4>
        <p class="text-muted small mb-0">Perbarui informasi detail aset dan foto pada form di bawah ini.</p>
    </div>

    <!-- Form Update -->
    <form action="{{ route('assets.update', $asset->id) }}" method="POST" enctype="multipart/form-data">
        @csrf 
        @method('PUT')
        
        <!-- Grid Form Input -->
        <div class="row g-4 mb-4">
            
            <!-- Kolom Kiri: Kode Aset -->
            <div class="col-md-6">
                <label class="form-label fw-bold">Kode Aset (S/N) <span class="text-danger">*</span></label>
                <input type="text" name="asset_code" class="form-control input-ui @error('asset_code') is-invalid @enderror" value="{{ old('asset_code', $asset->asset_code) }}" style="font-family: monospace; font-weight: 600; color: #4f46e5;">
                @error('asset_code')
                    <div class="invalid-feedback fw-semibold small">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Kolom Kanan: Nama Barang -->
            <div class="col-md-6">
                <label class="form-label fw-bold">Nama / Merk Barang <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control input-ui @error('name') is-invalid @enderror" value="{{ old('name', $asset->name) }}">
                @error('name')
                    <div class="invalid-feedback fw-semibold small">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Kolom Kiri: Kategori -->
            <div class="col-md-6">
                <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                <select name="category" class="form-select input-ui @error('category') is-invalid @enderror" style="cursor: pointer;">
                    @foreach (config('aset.kategori') as $value => $label)
                    <option value="{{ $value }}" @if(old('category', $asset->category) == $value) selected @endif>{{ $label }}</option>
                    @endforeach
                </select>
                @error('category')
                    <div class="invalid-feedback fw-semibold small">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Kolom Kanan: Kondisi -->
            <div class="col-md-6">
                <label class="form-label fw-bold">Kondisi <span class="text-danger">*</span></label>
                <select name="condition" class="form-select input-ui @error('condition') is-invalid @enderror" style="cursor: pointer;">
                    @foreach (config('aset.kondisi') as $value => $label)
                    <option value="{{ $value }}" @if(old('condition', $asset->condition) == $value) selected @endif>{{ $label }}</option>
                    @endforeach
                </select>
                @error('condition')
                    <div class="invalid-feedback fw-semibold small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Upload Foto & Preview -->
            <div class="col-md-6">
                <label class="form-label fw-bold">Perbarui Foto Fisik <span class="text-muted fw-normal text-capitalize" style="text-transform: none !important;">(Opsional)</span></label>
                
                @if($asset->image)
                    <div class="d-flex align-items-center mb-2 gap-3 p-2 rounded" style="background-color: #f8fafc; border: 1px dashed #cbd5e1;">
                        <img src="{{ asset('storage/' . $asset->image) }}" alt="Current Photo" class="rounded" style="width: 45px; height: 45px; object-fit: cover; border: 1px solid #e2e8f0;">
                        <span class="text-muted" style="font-size: 0.75rem; line-height: 1.3;">Foto saat ini terpasang.<br>Unggah baru untuk mengganti.</span>
                    </div>
                @endif
                
                <input type="file" name="image" class="form-control input-ui @error('image') is-invalid @enderror" accept="image/png, image/jpeg, image/jpg">
                @error('image')
                    <div class="invalid-feedback fw-semibold small">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Kolom Status Pinjaman -->
            <div class="col-md-6">
                <label class="form-label fw-bold">Dipinjamkan Kepada <span class="text-muted fw-normal text-capitalize" style="text-transform: none !important;">(Opsional)</span></label>
                <input type="text" name="assigned_to" class="form-control input-ui" placeholder="Kosongkan jika tersedia di Gudang IT..." value="{{ old('assigned_to', $asset->assigned_to) }}">
            </div>

            <!-- Full Width: Deskripsi Kendala -->
            <div class="col-12">
                <label class="form-label fw-bold">Deskripsi Kendala / Kerusakan <span class="text-muted fw-normal text-capitalize" style="text-transform: none !important;">(Isi jika aset rusak/perbaikan)</span></label>
                <textarea name="problem_description" class="form-control input-ui @error('problem_description') is-invalid @enderror" rows="3" placeholder="Misal: Layar bergaris, keyboard rusak, dll...">{{ old('problem_description', $asset->problem_description ?? '') }}</textarea>
                @error('problem_description')
                    <div class="invalid-feedback fw-semibold small">{{ $message }}</div>
                @enderror
            </div>

        </div>

        <!-- Tombol Aksi -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mt-4 pt-4 border-top gap-3">
            <a href="{{ route('assets.index') }}" class="btn btn-light-custom px-4 shadow-sm w-100 w-sm-auto text-center">
                Batal
            </a>
            <button type="submit" class="btn btn-primary-custom px-4 shadow-sm w-100 w-sm-auto d-flex justify-content-center align-items-center">
                <i class="bi bi-save me-2"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection