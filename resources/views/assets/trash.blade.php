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

    .badge-soft-danger { background-color: #fee2e2; color: #dc2626; font-weight: 600; padding: 0.4em 0.7em; border-radius: 6px; font-size: 0.75rem; }

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

    .action-btn { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; transition: 0.2s; border: none; }
    .action-btn:hover { transform: translateY(-2px); }
    .action-btn-success { background: #d1fae5; color: #059669; }
    .action-btn-danger { background: #fee2e2; color: #dc2626; }
</style>

<div class="card-dashboard p-4">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 pb-3 border-bottom gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: #0f172a; letter-spacing: -0.5px;">Kotak Sampah Aset</h4>
            <p class="text-muted small mb-0">Aset yang dihapus dari Data Inventaris. Masih dapat dipulihkan seperti semula.</p>
        </div>
        <a class="btn btn-light-custom shadow-sm d-flex align-items-center justify-content-center px-4 ms-md-auto" href="{{ route('assets.index') }}">
            <i class="bi bi-arrow-left fs-6 me-2"></i> Kembali ke Data Inventaris
        </a>
    </div>

    <!-- Tabel Aset Terhapus -->
    <div class="table-responsive">
        <table class="table table-custom table-hover align-middle text-nowrap mb-0 w-100">
            <thead>
                <tr>
                    <th>Kode / S/N</th>
                    <th>Item Barang</th>
                    <th>Kategori</th>
                    <th>Dihapus Pada</th>
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
                        <span class="badge-soft-danger"><i class="bi bi-clock-history me-1"></i> {{ $asset->deleted_at->format('d M Y H:i') }}</span>
                    </td>
                    <td class="text-center">
                        <div class="m-0 d-inline-flex gap-2 justify-content-center">
                            <form id="restore-form-{{ $asset->id }}" action="{{ route('assets.restore', $asset->id) }}" method="POST" class="m-0 d-inline-flex">
                                @csrf
                                <button type="button" class="action-btn action-btn-success" onclick="confirmRestore({{ $asset->id }})" title="Pulihkan">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </button>
                            </form>
                            <form id="force-form-{{ $asset->id }}" action="{{ route('assets.force', $asset->id) }}" method="POST" class="m-0 d-inline-flex">
                                @csrf @method('DELETE')
                                <button type="button" class="action-btn action-btn-danger" onclick="confirmForce({{ $asset->id }})" title="Hapus Permanen">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-2 d-block mb-2" style="color: #cbd5e1;"></i>
                        Kotak sampah kosong. Semua aset masih aktif.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer (Pagination) -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-4 border-top gap-3">
        <span class="text-muted fw-semibold small text-center text-md-start" style="font-size: 0.85rem;">
            Menampilkan <span class="text-dark">{{ $assets->firstItem() ?? 0 }}</span> - <span class="text-dark">{{ $assets->lastItem() ?? 0 }}</span> dari <span class="text-dark">{{ $assets->total() }}</span> aset terhapus
        </span>
        <div class="d-flex justify-content-center overflow-auto w-100 w-md-auto">
            {{ $assets->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<script>
    function confirmRestore(id) {
        Swal.fire({
            title: 'Pulihkan Aset?',
            text: "Aset akan dikembalikan ke Data Inventaris seperti semula.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Pulihkan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('restore-form-' + id).submit();
            }
        });
    }

    function confirmForce(id) {
        Swal.fire({
            title: 'Hapus Permanen?',
            text: "Aset beserta file gambarnya akan dihapus selamanya dan tidak dapat dipulihkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f01414',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus Permanen',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('force-form-' + id).submit();
            }
        });
    }
</script>
@endsection
