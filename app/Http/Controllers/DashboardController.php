<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Metrik Utama
        $totalAset = Asset::count();
        $asetBaik = Asset::where('condition', 'Baik')->count();
        $asetPerbaikan = Asset::where('condition', 'Perbaikan')->count();
        $asetRusak = Asset::where('condition', 'Rusak')->count();

        // 2. Data Grafik Kategori (Bar Chart)
        $kategoriLabel = ['Laptop', 'PC Desktop', 'Printer', 'Router'];
        $kategoriData = [];
        foreach ($kategoriLabel as $kat) {
            // Karena sebelumnya mungkin ada data 'PC' atau 'PC Desktop', kita gabungkan
            if($kat == 'PC Desktop') {
                $kategoriData[] = Asset::whereIn('category', ['PC', 'PC Desktop'])->count();
            } else {
                $kategoriData[] = Asset::where('category', $kat)->count();
            }
        }

        // 3. Tabel Aset Terbaru (5 Data Terakhir)
        $asetTerbaru = Asset::latest()->take(5)->get();

        return view('assets.dashboard', compact(
            'totalAset', 'asetBaik', 'asetPerbaikan', 'asetRusak',
            'kategoriLabel', 'kategoriData', 'asetTerbaru'
        ));
    }
}