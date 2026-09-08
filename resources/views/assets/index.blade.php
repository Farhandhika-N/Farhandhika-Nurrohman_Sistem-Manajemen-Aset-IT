@extends('assets.layout')
@section('content')
    <div class="card-ui">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <h4 class="mb-3 mb-md-0 fw-bold text-center text-md-start" style="color: #2c3e50;">Data Inventaris Aset IT</h4>
            <div class="text-center text-md-end">
                <a class="btn btn-action btn-action-primary shadow-sm w-100 w-md-auto" href="{{ route('assets.create') }}">+ Tambah Aset Baru</a>
            </div>
        </div>
        
        <hr class="mt-3 mb-4" style="border-color: #e9ecef;">

        <!-- Area Kontrol (Filter & Search) -->
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-3">
            <!-- Filter -->
            <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-2">
                <span class="text-muted d-none d-sm-inline" style="font-size: 0.85rem;">Filter:</span>
                <select id="filterCategory" class="input-ui filter-input">
                    <option value="">Semua Kategori</option>
                    <option value="Laptop" {{ request('category') == 'Laptop' ? 'selected' : '' }}>Laptop</option>
                    <option value="PC Desktop" {{ request('category') == 'PC Desktop' ? 'selected' : '' }}>PC Desktop</option>
                    <option value="Printer" {{ request('category') == 'Printer' ? 'selected' : '' }}>Printer</option>
                    <option value="Router" {{ request('category') == 'Router' ? 'selected' : '' }}>Router</option>
                </select>
                <select id="filterCondition" class="input-ui filter-input">
                    <option value="">Semua Kondisi</option>
                    <option value="Baik" {{ request('condition') == 'Baik' ? 'selected' : '' }}>Baik</option>
                    <option value="Perbaikan" {{ request('condition') == 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                    <option value="Rusak" {{ request('condition') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                </select>
            </div>

            <!-- Pencarian -->
            <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-2">
                <span class="text-muted d-none d-sm-inline" style="font-size: 0.85rem;">Search:</span>
                <input type="search" id="filterSearch" class="input-ui search-input" placeholder="Ketik pencarian..." value="{{ request('search') }}" autocomplete="off">
            </div>
        </div>

        <!-- Tabel Data -->
        <div id="tableContainer">
            <div class="table-responsive">
                <table class="table-ui">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Kondisi</th>
                            <th>Pemakai</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($assets as $asset)
                        <tr>
                            <td class="text-muted fw-semibold">{{ $asset->asset_code }}</td>
                            <td class="fw-semibold" style="color: #343a40;">{{ $asset->name }}</td>
                            <td>{{ $asset->category }}</td>
                            <td>
                                @if($asset->condition == 'Baik')
                                    <span class="badge" style="background-color: #e3fbed; color: #28c76f;">Baik</span>
                                @elseif($asset->condition == 'Rusak')
                                    <span class="badge" style="background-color: #fce4e4; color: #ea5455;">Rusak</span>
                                @else
                                    <span class="badge" style="background-color: #fdf3e1; color: #ff9f43;">Perbaikan</span>
                                @endif
                            </td>
                            <td>{{ $asset->assigned_to ?? '-' }}</td>
                            <td class="text-center action-buttons">
                                <form id="delete-form-{{ $asset->id }}" action="{{ route('assets.destroy', $asset->id) }}" method="POST" class="m-0 d-inline-flex gap-1">
                                    <a class="btn-action btn-action-info" href="{{ route('assets.show', $asset->id) }}">Detail</a>
                                    <a class="btn-action btn-action-warning" href="{{ route('assets.edit', $asset->id) }}">Edit</a>
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-action btn-action-danger" onclick="confirmDelete({{ $asset->id }})">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Data aset tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer (Pagination) -->
            <div class="d-flex flex-column align-items-center mt-3 border-top pt-3">
                <span class="text-muted fw-semibold mb-2 text-center" style="font-size: 0.85rem;">
                    Showing {{ $assets->firstItem() ?? 0 }} to {{ $assets->lastItem() ?? 0 }} of {{ $assets->total() }} records
                </span>
                <div class="w-100 d-flex justify-content-center">
                    {{ $assets->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data aset ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f43f5e', /* Menyesuaikan dengan warna merah tombol Hapus yang baru */
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        function fetchFilteredData() {
            let search = document.getElementById('filterSearch').value;
            let category = document.getElementById('filterCategory').value;
            let condition = document.getElementById('filterCondition').value;

            let url = new URL("{{ route('assets.index') }}");
            if (search) url.searchParams.append('search', search);
            if (category) url.searchParams.append('category', category);
            if (condition) url.searchParams.append('condition', condition);

            fetch(url)
                .then(response => response.text())
                .then(html => {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html');
                    document.getElementById('tableContainer').innerHTML = doc.getElementById('tableContainer').innerHTML;
                })
                .catch(error => console.log('Error fetching data:', error));
        }

        document.getElementById('filterSearch').addEventListener('input', fetchFilteredData);
        document.getElementById('filterCategory').addEventListener('change', fetchFilteredData);
        document.getElementById('filterCondition').addEventListener('change', fetchFilteredData);
    </script>
@endsection