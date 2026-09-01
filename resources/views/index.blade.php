@extends('assets.layout')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>Data Aset IT</h4>
        <a class="btn btn-primary" href="{{ route('assets.create') }}">Tambah Aset</a>
    </div>
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr><th>Kode</th><th>Nama</th><th>Kategori</th><th>Kondisi</th><th>Pemakai</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @foreach ($assets as $asset)
            <tr>
                <td>{{ $asset->asset_code }}</td><td>{{ $asset->name }}</td><td>{{ $asset->category }}</td>
                <td>{{ $asset->condition }}</td><td>{{ $asset->assigned_to ?? 'Gudang' }}</td>
                <td>
                    <form action="{{ route('assets.destroy', $asset->id) }}" method="POST">
                        <a class="btn btn-sm btn-warning" href="{{ route('assets.edit', $asset->id) }}">Edit</a>
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $assets->links('pagination::bootstrap-5') }}
@endsection