<?php

namespace App\Exports;

use App\Models\Asset;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssetsExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(protected Request $request)
    {
    }

    public function query()
    {
        return Asset::query()
            ->filter($this->request)
            ->latest();
    }

    public function headings(): array
    {
        return [
            'Kode Aset (S/N)',
            'Nama / Merk Barang',
            'Kategori',
            'Kondisi',
            'Deskripsi Kendala',
            'Status / Pemakai',
            'Tanggal Input',
        ];
    }

    public function map($asset): array
    {
        return [
            $asset->asset_code,
            $asset->name,
            $asset->category,
            $asset->condition,
            $asset->problem_description ?? '-',
            $asset->assigned_to ?? 'Gudang (Tersedia)',
            $asset->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
