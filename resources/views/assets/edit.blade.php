@extends('assets.layout')
@section('content')
    <h4>Edit Aset</h4>
    <form action="{{ route('assets.update', $asset->id) }}" method="POST" class="mt-3">
        @csrf @method('PUT')
        <input type="text" name="asset_code" class="form-control mb-2" value="{{ $asset->asset_code }}" required>
        <input type="text" name="name" class="form-control mb-2" value="{{ $asset->name }}" required>
        <select name="category" class="form-control mb-2">
            <option @if($asset->category=='Laptop') selected @endif>Laptop</option>
            <option @if($asset->category=='PC') selected @endif>PC</option>
            <option @if($asset->category=='Printer') selected @endif>Printer</option>
            <option @if($asset->category=='Jaringan') selected @endif>Jaringan</option>
        </select>
        <select name="condition" class="form-control mb-2">
            <option @if($asset->condition=='Baik') selected @endif>Baik</option>
            <option @if($asset->condition=='Perbaikan') selected @endif>Perbaikan</option>
            <option @if($asset->condition=='Rusak') selected @endif>Rusak</option>
        </select>
        <input type="text" name="assigned_to" class="form-control mb-3" value="{{ $asset->assigned_to }}">
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('assets.index') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection