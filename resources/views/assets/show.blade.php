@extends('assets.layout')
@section('content')
<div class="card-ui mx-auto" style="max-width: 800px;"> 
    
    <!-- Judul Halaman -->
    <div class="mb-4 text-center text-md-start">
        <h4 class="mb-0 fw-bold" style="color: #2c3e50;">Detail Aset IT</h4>
    </div>

    <!-- Card Informasi Aset -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px; overflow: hidden; border: 1px solid #f1f3f5 !important;">
        <div class="card-header border-0 py-3 text-center text-md-start" style="background-color: #3b82f6; color: white;">
            <h5 class="mb-0 fw-semibold" style="font-size: 1.05rem;">Informasi Aset: {{ $asset->asset_code }}</h5>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size: 0.95rem; min-width: 400px;">
                    <tbody>
                        <tr>
                            <th class="ps-4 py-3 text-muted align-middle" style="width: 35%; background-color: #f8f9fa; font-weight: 600; border-bottom: 1px solid #f1f3f5;">
                                Kode Aset (S/N)
                            </th>
                            <td class="py-3 px-3 align-middle" style="border-bottom: 1px solid #f1f3f5;">
                                : <strong style="color: #2c3e50; margin-left: 5px;">{{ $asset->asset_code }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <th class="ps-4 py-3 text-muted align-middle" style="background-color: #f8f9fa; font-weight: 600; border-bottom: 1px solid #f1f3f5;">
                                Nama / Merk Barang
                            </th>
                            <td class="py-3 px-3 align-middle" style="border-bottom: 1px solid #f1f3f5;">
                                : <span style="color: #343a40; margin-left: 5px;">{{ $asset->name }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th class="ps-4 py-3 text-muted align-middle" style="background-color: #f8f9fa; font-weight: 600; border-bottom: 1px solid #f1f3f5;">
                                Kategori
                            </th>
                            <td class="py-3 px-3 align-middle" style="border-bottom: 1px solid #f1f3f5;">
                                : <span style="margin-left: 5px;">{{ $asset->category }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th class="ps-4 py-3 text-muted align-middle" style="background-color: #f8f9fa; font-weight: 600; border-bottom: 1px solid #f1f3f5;">
                                Kondisi Saat Ini
                            </th>
                            <td class="py-3 px-3 align-middle" style="border-bottom: 1px solid #f1f3f5;">
                                : <div class="d-inline-block" style="margin-left: 5px;">
                                    @if($asset->condition == 'Baik')
                                        <span class="badge" style="background-color: #e3fbed; color: #28c76f; padding: 6px 10px;">Baik</span>
                                    @elseif($asset->condition == 'Rusak')
                                        <span class="badge" style="background-color: #fce4e4; color: #ea5455; padding: 6px 10px;">Rusak</span>
                                    @else
                                        <span class="badge" style="background-color: #fdf3e1; color: #ff9f43; padding: 6px 10px;">Sedang Perbaikan</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="ps-4 py-3 text-muted align-middle" style="background-color: #f8f9fa; font-weight: 600; border-bottom: 1px solid #f1f3f5;">
                                Status Pemakaian
                            </th>
                            <td class="py-3 px-3 align-middle" style="border-bottom: 1px solid #f1f3f5;">
                                : <div class="d-inline-block" style="margin-left: 5px;">
                                    @if($asset->assigned_to)
                                        Dipinjamkan kepada <strong style="color: #2c3e50;">{{ $asset->assigned_to }}</strong>
                                    @else
                                        <span class="fw-bold" style="color: #28c76f;">Tersedia di Gudang IT</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="ps-4 py-3 text-muted align-middle" style="background-color: #f8f9fa; font-weight: 600; border-bottom: 1px solid #f1f3f5;">
                                Tanggal Input Data
                            </th>
                            <td class="py-3 px-3 align-middle" style="border-bottom: 1px solid #f1f3f5;">
                                : <span style="margin-left: 5px;">{{ $asset->created_at->translatedFormat('d F Y - H:i') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th class="ps-4 py-3 text-muted align-middle" style="background-color: #f8f9fa; font-weight: 600; border-bottom: 0;">
                                Terakhir Diupdate
                            </th>
                            <td class="py-3 px-3 align-middle" style="border-bottom: 0;">
                                : <span style="margin-left: 5px;">{{ $asset->updated_at->translatedFormat('d F Y - H:i') }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tombol Kembali Dipindah Ke Bawah -->
    <div class="text-center text-md-start">
        <a class="btn btn-sm btn-light border text-muted px-4 shadow-sm" style="border-radius: 6px;" href="{{ route('assets.index') }}">
            &larr; Kembali ke Daftar
        </a>
    </div>
</div>
@endsection