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

    .badge-soft-indigo { background-color: #e0e7ff; color: #4338ca; font-weight: 600; padding: 0.4em 0.7em; border-radius: 6px; font-size: 0.75rem; }
    .badge-soft-slate { background-color: #f1f5f9; color: #475569; font-weight: 600; padding: 0.4em 0.7em; border-radius: 6px; font-size: 0.75rem; }

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
    .action-btn-warning { background: #fef3c7; color: #d97706; }
    .action-btn-danger { background: #fee2e2; color: #dc2626; }
</style>

<div class="card-dashboard p-4">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 pb-3 border-bottom gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: #0f172a; letter-spacing: -0.5px;">Manajemen User</h4>
            <p class="text-muted small mb-0">Kelola akun pengguna beserta role (hak akses) sistem inventaris.</p>
        </div>
        <div class="d-flex flex-column flex-sm-row gap-2 ms-md-auto w-100 w-md-auto justify-content-md-end">
            <form action="{{ route('users.index') }}" method="GET" class="d-flex gap-2 flex-grow-1" style="min-width: 240px;">
                <div class="input-group flex-grow-1" style="border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; background: #fff;">
                    <span class="input-group-text bg-white border-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="search" name="search" class="form-control border-0 bg-white shadow-none py-2" placeholder="Cari nama atau email..." value="{{ request('search') }}" autocomplete="off" style="font-size: 0.85rem;">
                </div>
                <button type="submit" class="btn btn-light-custom px-3 shadow-sm">Cari</button>
            </form>
            <a class="btn btn-primary-custom shadow-sm d-flex align-items-center justify-content-center px-4" href="{{ route('users.create') }}">
                <i class="bi bi-person-plus fs-6 me-2"></i> Tambah User
            </a>
        </div>
    </div>

    <!-- Tabel User -->
    <div class="table-responsive">
        <table class="table table-custom table-hover align-middle text-nowrap mb-0 w-100">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold small text-white flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.75rem; background-color: {{ $user->role === 'admin' ? '#4f46e5' : '#64748b' }};">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <span class="fw-bold text-dark">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'admin')
                            <span class="badge-soft-indigo"><i class="bi bi-shield-check me-1"></i> Administrator</span>
                        @else
                            <span class="badge-soft-slate"><i class="bi bi-person me-1"></i> Staff</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="m-0 d-inline-flex gap-2 justify-content-center">
                            <a class="action-btn action-btn-warning" href="{{ route('users.edit', $user->id) }}" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form id="delete-user-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" class="m-0 d-inline-flex">
                                @csrf @method('DELETE')
                                <button type="button" class="action-btn action-btn-danger" onclick="confirmDeleteUser({{ $user->id }})" title="Hapus">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-2 d-block mb-2" style="color: #cbd5e1;"></i>
                        Data user tidak ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer (Pagination) -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-4 border-top gap-3">
        <span class="text-muted fw-semibold small text-center text-md-start" style="font-size: 0.85rem;">
            Menampilkan <span class="text-dark">{{ $users->firstItem() ?? 0 }}</span> - <span class="text-dark">{{ $users->lastItem() ?? 0 }}</span> dari <span class="text-dark">{{ $users->total() }}</span> total user
        </span>
        <div class="d-flex justify-content-center overflow-auto w-100 w-md-auto">
            {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<script>
    function confirmDeleteUser(id) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: "Akun user ini akan dihapus dan tidak dapat login lagi!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f01414',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus Akun',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-user-form-' + id).submit();
            }
        });
    }
</script>
@endsection
