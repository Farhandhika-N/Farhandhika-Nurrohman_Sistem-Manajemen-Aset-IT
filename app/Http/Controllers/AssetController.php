<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController
{
    // 1. READ: Menampilkan data & Fitur Pencarian
    public function index(Request $request) 
    {
        $query = Asset::query();
        // 1. Logika Pencarian Teks
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('asset_code', 'like', "%{$search}%")
                ->orWhere('assigned_to', 'like', "%{$search}%");
            });
        }

        // 2. Logika Filter Kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // 3. Logika Filter Kondisi
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        $assets = $query->latest()->paginate(10);
        $assets->appends($request->all()); 

        return view('assets.index', compact('assets'));
    }

    // 2. CREATE: Menampilkan form tambah aset
    public function create() 
    {
        return view('assets.create');
    }

    // 3. STORE: Menyimpan data ke database
    public function store(Request $request) 
    {
        $request->validate([
            'asset_code' => 'required|unique:assets',
            'name' => 'required',
            'category' => 'required',
            'condition' => 'required',
        ]);

        Asset::create($request->all());
        
        return redirect()->route('assets.index')
            ->with('success', 'Aset IT berhasil ditambahkan.');
    }

    // 4. SHOW: Menampilkan detail aset 
    public function show(Asset $asset) 
    {
        return view('assets.show', compact('asset'));
    }

    // 5. EDIT: Menampilkan form edit aset
    public function edit(Asset $asset) 
    {
        return view('assets.edit', compact('asset'));
    }

    // 6. UPDATE: Memperbarui data ke database
    public function update(Request $request, Asset $asset) 
    {
        $request->validate([
            'asset_code' => 'required|unique:assets,asset_code,'.$asset->id,
            'name' => 'required',
            'category' => 'required',
            'condition' => 'required',
        ]);

        $asset->update($request->all());
        
        return redirect()->route('assets.index')
            ->with('success', 'Data Aset IT berhasil diperbarui.');
    }

    // 7. DESTROY: Menghapus data dari database
    public function destroy(Asset $asset) 
    {
        $asset->delete();
        
        return redirect()->route('assets.index')
            ->with('success', 'Aset IT berhasil dihapus.');
    }
}