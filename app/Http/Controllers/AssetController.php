<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssetController extends Controller 
{
    // 1. READ: Menampilkan data & Fitur Pencarian
    public function index(Request $request) 
    {
        $query = Asset::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('asset_code', 'like', "%{$search}%")
                ->orWhere('assigned_to', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        $assets = $query->latest()->paginate(10);
        $assets->appends($request->all()); 

        return view('assets.index', compact('assets'));
    }

    // FITUR Export Excel
    public function exportExcel(Request $request)
    {
        $fileName = 'Laporan_Aset_IT_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new class($request) implements FromQuery, WithHeadings, WithMapping {
            protected $request;

            public function __construct($request) {
                $this->request = $request;
            }

            public function query() {
                $query = Asset::query();

                if ($this->request->filled('search')) {
                    $search = $this->request->search;
                    $query->where(function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('asset_code', 'like', "%{$search}%")
                          ->orWhere('assigned_to', 'like', "%{$search}%");
                    });
                }

                if ($this->request->filled('category')) {
                    $query->where('category', $this->request->category);
                }

                if ($this->request->filled('condition')) {
                    $query->where('condition', $this->request->condition);
                }

                return $query->latest(); 
            }

            public function headings(): array {
                return [
                    'Kode Aset (S/N)',
                    'Nama / Merk Barang',
                    'Kategori',
                    'Kondisi',
                    'Deskripsi Kendala',
                    'Status / Pemakai',
                    'Tanggal Input'
                ];
            }

            public function map($asset): array {
                return [
                    $asset->asset_code,
                    $asset->name,
                    $asset->category,
                    $asset->condition,
                    $asset->problem_description ?? '-',
                    $asset->assigned_to ?? 'Gudang (Tersedia)',
                    $asset->created_at->format('Y-m-d H:i:s')
                ];
            }
        }, $fileName);
    }

    // 2. CREATE: Menampilkan form tambah aset
    public function create() 
    {
        return view('assets.create');
    }

    // 3. STORE: Menyimpan data ke database
    public function store(Request $request) 
    {
        // Validasi yang diperbarui dengan kustomisasi pesan error
        $request->validate([
            'asset_code' => 'required|unique:assets',
            'name' => 'required',
            'category' => 'required',
            'condition' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi file gambar maks 2MB
        ], [
            'asset_code.required' => 'Kode Aset (S/N) wajib diisi.',
            'asset_code.unique' => 'Kode Aset sudah terdaftar di sistem.',
            'name.required' => 'Nama / Merk barang wajib diisi.',
            'category.required' => 'Silakan pilih kategori.',
            'condition.required' => 'Silakan pilih kondisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'image.max' => 'Ukuran file gambar maksimal 2MB.',
        ]);

        $data = $request->all();

        // Menyimpan file gambar jika diunggah
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('assets_images', 'public');
        }

        Asset::create($data);
        
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'asset_code.required' => 'Kode Aset (S/N) wajib diisi.',
            'asset_code.unique' => 'Kode Aset sudah terdaftar di sistem.',
            'name.required' => 'Nama / Merk barang wajib diisi.',
            'category.required' => 'Silakan pilih kategori.',
            'condition.required' => 'Silakan pilih kondisi.',
        ]);

        $data = $request->all();

        // Mengganti gambar jika ada file baru yang diunggah
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($asset->image) {
                Storage::disk('public')->delete($asset->image);
            }
            $data['image'] = $request->file('image')->store('assets_images', 'public');
        }

        $asset->update($data);
        
        return redirect()->route('assets.index')
            ->with('success', 'Data Aset IT berhasil diperbarui.');
    }

    // 7. DESTROY: Menghapus data dari database
    public function destroy(Asset $asset) 
    {
        // Hapus file gambar fisik dari direktori storage sebelum menghapus data DB
        if ($asset->image) {
            Storage::disk('public')->delete($asset->image);
        }
        
        $asset->delete();
        
        return redirect()->route('assets.index')
            ->with('success', 'Aset IT berhasil dihapus.');
    }
}