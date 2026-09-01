@extends('assets.layout')
@section('content')
    <h4>Tambah Aset</h4>
    <form action="{{ route('assets.store') }}" method="POST" class="mt-3">
        @csrf
        <input type="text" name="asset_code" class="form-control mb-2" placeholder="Kode Aset" required>
        <input type="text" name="name" class="form-control mb-2" placeholder="Nama Barang" required>
        <select name="category" class="form-control mb-2">
            <option>Laptop</option><option>PC</option><option>Printer</option><option>Jaringan</option>
        </select>
        <select name="condition" class="form-control mb-2">
            <option>Baik</option><option>Perbaikan</option><option>Rusak</option>
        </select>
        <input type="text" name="assigned_to" class="form-control mb-3" placeholder="Dipinjamkan ke (Kosongi jika gudang)">
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('assets.index') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection