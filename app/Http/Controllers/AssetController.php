<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
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

    // FITUR DASHBOARD
    public function dashboard()
    {
        // Menghitung metrik KPI
        $totalAset = Asset::count();
        $asetBaik = Asset::where('condition', 'Baik')->count();
        $asetPerbaikan = Asset::where('condition', 'Perbaikan')->count();
        $asetRusak = Asset::where('condition', 'Rusak')->count();

        // Data untuk Bar Chart (Kategori)
        $kategoriDataRaw = Asset::selectRaw('category, count(*) as total')
                                ->groupBy('category')
                                ->pluck('total', 'category');
        $kategoriLabel = $kategoriDataRaw->keys()->toArray();
        $kategoriData = $kategoriDataRaw->values()->toArray();

        // Data untuk Tabel Register Terbaru (5 item terakhir)
        $asetTerbaru = Asset::latest()->take(5)->get();

        // [PERBAIKAN] Mengambil 5 Log Aktivitas Terakhir untuk Widget Dashboard
        $recentHistories = AssetHistory::with(['asset', 'user'])->latest()->take(5)->get();

        // [PERBAIKAN] Jangan lupa variabelnya dimasukkan ke compact()
        return view('assets.dashboard', compact(
            'totalAset', 'asetBaik', 'asetPerbaikan', 'asetRusak',
            'kategoriLabel', 'kategoriData', 'asetTerbaru', 'recentHistories'
        ));
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
        $request->validate([
            'asset_code' => 'required|unique:assets',
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
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'image.max' => 'Ukuran file gambar maksimal 2MB.',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('assets_images', 'public');
        }

        $asset = Asset::create($data);
        
        if (auth()->check()) {
            AssetHistory::create([
                'asset_id' => $asset->id,
                'user_id' => auth()->id(),
                'action' => 'Registrasi Aset Baru',
                'notes' => 'Aset ditambahkan ke dalam sistem dengan kondisi ' . $asset->condition,
            ]);
        }

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

        if ($request->hasFile('image')) {
            if ($asset->image) {
                Storage::disk('public')->delete($asset->image);
            }
            $data['image'] = $request->file('image')->store('assets_images', 'public');
        }

        $asset->fill($data);
        
        if (auth()->check()) {
            if ($asset->isDirty('assigned_to')) {
                $notes = $asset->assigned_to ? 'Dipinjamkan kepada: ' . $asset->assigned_to : 'Dikembalikan ke Gudang IT';
                AssetHistory::create([
                    'asset_id' => $asset->id,
                    'user_id' => auth()->id(),
                    'action' => 'Mutasi Pemakai',
                    'notes' => $notes,
                ]);
            }

            if ($asset->isDirty('condition')) {
                AssetHistory::create([
                    'asset_id' => $asset->id,
                    'user_id' => auth()->id(),
                    'action' => 'Perubahan Kondisi',
                    'notes' => 'Kondisi diubah menjadi: ' . $asset->condition . '. Catatan: ' . ($asset->problem_description ?? '-'),
                ]);
            }
        }
        
        $asset->save();
        
        return redirect()->route('assets.index')
            ->with('success', 'Data Aset IT berhasil diperbarui.');
    }

// 7. DESTROY: Menghapus data dari database
    public function destroy(Asset $asset) 
    {
        // 1. Simpan nama dan kode untuk dicatat di log karena datanya akan lenyap
        $assetName = $asset->name;
        $assetCode = $asset->asset_code;
        $assetImage = $asset->image;

        // 2. Hapus permanen data aset dari database 
        // (Berkat migration baru, log lama tidak akan hilang, asset_id-nya otomatis berubah jadi NULL)
        $asset->delete();
        
        // 3. CATAT LOG PENGHAPUSAN
        if (auth()->check()) {
            AssetHistory::create([
                'asset_id' => null, // Sekarang database sudah mengizinkan NULL
                'user_id' => auth()->id(),
                'action' => 'Penghapusan Aset',
                'notes' => "Aset '{$assetName}' (S/N: {$assetCode}) telah dihapus permanen dari sistem.",
            ]);
        }

        // 4. Hapus file gambar fisik dari storage jika ada
        if ($assetImage) {
            Storage::disk('public')->delete($assetImage);
        }
        
        return redirect()->route('assets.index')
            ->with('success', 'Aset IT berhasil dihapus permanen.');
    }

// FITUR HISTORY LOG MUTASI
    public function history(Request $request)
    {
        $query = AssetHistory::with(['asset', 'user']);

        // 1. Filter Pencarian Teks
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQ) use ($search) {
                      $userQ->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('asset', function($assetQ) use ($search) {
                      $assetQ->where('name', 'like', "%{$search}%")
                             ->orWhere('asset_code', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Filter Berdasarkan Jenis Aksi
        if ($request->filled('action_filter')) {
            $query->where('action', $request->action_filter);
        }

        // 3. Filter Berdasarkan Waktu (Diperbaiki agar 'week' akurat 1 minggu penuh)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
            $endDate   = \Carbon\Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $histories = $query->latest()->paginate(15);
        $histories->appends($request->all()); 

        if ($request->ajax()) {
            return view('assets.history', compact('histories'));
        }

        return view('assets.history', compact('histories'));
    }

    // FITUR CETAK PDF LOG MUTASI (DENGAN FILTER & PERBAIKAN WAKTU)
    public function exportHistoryPDF(Request $request)
    {
        $query = AssetHistory::with(['asset', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQ) use ($search) {
                      $userQ->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('asset', function($assetQ) use ($search) {
                      $assetQ->where('name', 'like', "%{$search}%")
                             ->orWhere('asset_code', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('action_filter')) {
            $query->where('action', $request->action_filter);
        }

        if ($request->filled('time_filter')) {
            $time = $request->time_filter;
            if ($time == 'today') {
                $query->whereDate('created_at', \Carbon\Carbon::today());
            } elseif ($time == 'week') {
                $startOfWeek = \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY)->startOfDay();
                $endOfWeek   = \Carbon\Carbon::now()->endOfWeek(\Carbon\Carbon::SUNDAY)->endOfDay();
                $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
            } elseif ($time == 'month') {
                $query->whereMonth('created_at', \Carbon\Carbon::now()->month)
                      ->whereYear('created_at', \Carbon\Carbon::now()->year);
            }
        }

        $histories = $query->latest()->get();
        $pdf = Pdf::loadView('assets.pdf_history', compact('histories'))->setPaper('a4', 'portrait');
        
        return $pdf->stream('Laporan_Log_Mutasi_Aset_IT.pdf');
    }

}