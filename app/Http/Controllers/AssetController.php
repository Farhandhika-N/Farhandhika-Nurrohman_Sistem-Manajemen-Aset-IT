<?php

namespace App\Http\Controllers;

use App\Exports\AssetsExport;
use App\Models\Asset;
use App\Models\AssetHistory;
use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AssetController extends Controller 
{
    // 1. READ: Menampilkan data & Fitur Pencarian
    public function index(Request $request) 
    {
        $assets = Asset::filter($request)->latest()->paginate(10);
        $assets->appends($request->all()); 

        return view('assets.index', compact('assets'));
    }

    // FITUR DASHBOARD
    public function dashboard()
    {
        $totalAset = Asset::count();
        $asetBaik = Asset::where('condition', 'Baik')->count();
        $asetPerbaikan = Asset::where('condition', 'Perbaikan')->count();
        $asetRusak = Asset::where('condition', 'Rusak')->count();

        $kategoriDataRaw = Asset::selectRaw('category, count(*) as total')
                                ->groupBy('category')
                                ->pluck('total', 'category');
        $kategoriLabel = $kategoriDataRaw->keys()->toArray();
        $kategoriData = $kategoriDataRaw->values()->toArray();

        $asetTerbaru = Asset::latest()->take(5)->get();

        $recentHistories = AssetHistory::with(['asset', 'user'])->latest()->take(5)->get();

        return view('assets.dashboard', compact(
            'totalAset', 'asetBaik', 'asetPerbaikan', 'asetRusak',
            'kategoriLabel', 'kategoriData', 'asetTerbaru', 'recentHistories'
        ));
    }

    // FITUR Export Excel
    public function exportExcel(Request $request)
    {
        $fileName = 'Laporan_Aset_IT_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new AssetsExport($request), $fileName);
    }

    // FITUR CETAK PDF DAFTAR ASET (mengikuti filter yang sedang aktif)
    public function exportPDF(Request $request)
    {
        $assets = Asset::filter($request)->latest()->get();

        $pdf = Pdf::loadView('assets.pdf_assets', compact('assets'))->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Data_Aset_IT.pdf');
    }

    // 2. CREATE: Menampilkan form tambah aset
    public function create() 
    {
        return view('assets.create');
    }

    // 3. STORE: Menyimpan data ke database
    public function store(StoreAssetRequest $request) 
    {
        $data = $request->validated();

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
        return view('assets.show', ['asset' => $asset->load('histories.user')]);
    }

    // 5. EDIT: Menampilkan form edit aset
    public function edit(Asset $asset) 
    {
        return view('assets.edit', compact('asset'));
    }

    // 6. UPDATE: Memperbarui data ke database
    public function update(UpdateAssetRequest $request, Asset $asset) 
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($asset->image) {
                Storage::disk('public')->delete($asset->image);
            }
            $data['image'] = $request->file('image')->store('assets_images', 'public');
        } else {
            unset($data['image']);
        }

        $asset->fill($data)->save();

        return redirect()->route('assets.index')
            ->with('success', 'Data Aset IT berhasil diperbarui.');
    }

    // 7. DESTROY: Pindahkan aset ke Kotak Sampah
    public function destroy(Asset $asset) 
    {
        $asset->delete();

        return redirect()->route('assets.index')
            ->with('success', 'Aset IT dipindahkan ke Kotak Sampah.');
    }

    // KOTAK SAMPAH: Daftar aset terhapus (soft delete)
    public function trash()
    {
        $assets = Asset::onlyTrashed()->latest('deleted_at')->paginate(10);

        return view('assets.trash', compact('assets'));
    }

    // KOTAK SAMPAH: Mengembalikan aset seperti semula
    public function restore(Asset $asset)
    {
        $asset->restore();

        return redirect()->route('assets.trash')
            ->with('success', 'Aset berhasil dipulihkan dari Kotak Sampah.');
    }

    // KOTAK SAMPAH: Menghapus aset beserta file gambar secara permanen
    public function forceDestroy(Asset $asset)
    {
        $asset->forceDelete();

        return redirect()->route('assets.trash')
            ->with('success', 'Aset dihapus permanen dari sistem.');
    }

    // FITUR HISTORY LOG MUTASI
    public function history(Request $request)
    {
        $histories = AssetHistory::with(['asset', 'user'])
            ->filter($request)
            ->latest()
            ->paginate(15);
        $histories->appends($request->all()); 

        return view('assets.history', compact('histories'));
    }

    // FITUR CETAK PDF LOG MUTASI (DENGAN FILTER & PERBAIKAN WAKTU)
    public function exportHistoryPDF(Request $request)
    {
        $histories = AssetHistory::with(['asset', 'user'])
            ->filter($request)
            ->latest()
            ->get();
        $pdf = Pdf::loadView('assets.pdf_history', compact('histories'))->setPaper('a4', 'portrait');
        
        return $pdf->stream('Laporan_Log_Mutasi_Aset_IT.pdf');
    }

}