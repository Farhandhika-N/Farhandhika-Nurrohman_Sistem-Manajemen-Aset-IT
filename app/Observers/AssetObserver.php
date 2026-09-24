<?php

namespace App\Observers;

use App\Models\Asset;
use App\Models\AssetHistory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

/**
 * Pencatat jejak audit (log mutasi) untuk setiap perubahan aset.
 * Dipasang di AppServiceProvider agar semua perubahan tercatat otomatis.
 */
class AssetObserver
{
    /**
     * Label Indonesia untuk field aset.
     */
    private const FIELD_LABELS = [
        'asset_code' => 'Kode Aset',
        'name' => 'Nama Barang',
        'category' => 'Kategori',
        'problem_description' => 'Deskripsi Kendala',
        'assigned_to' => 'Dipinjamkan Kepada',
        'image' => 'Foto Barang',
    ];

    /**
     * Aset baru didaftarkan ke sistem.
     */
    public function created(Asset $asset): void
    {
        $this->log($asset->id, 'Registrasi Aset Baru', 'Aset ditambahkan ke dalam sistem dengan kondisi ' . $asset->condition);
    }

    /**
     * Setiap field yang berubah dicatat otomatis.
     */
    public function updated(Asset $asset): void
    {
        $changes = Arr::except($asset->getChanges(), ['created_at', 'updated_at', 'deleted_at']);

        if (empty($changes)) {
            return;
        }

        // 1. Mutasi pemakai
        if (array_key_exists('assigned_to', $changes)) {
            $notes = $asset->assigned_to ? 'Dipinjamkan kepada: ' . $asset->assigned_to : 'Dikembalikan ke Gudang IT';
            $this->log($asset->id, 'Mutasi Pemakai', $notes);
        }

        // 2. Perubahan kondisi
        if (array_key_exists('condition', $changes)) {
            $this->log($asset->id, 'Perubahan Kondisi', 'Kondisi diubah menjadi: ' . $asset->condition . '. Catatan: ' . ($asset->problem_description ?? '-'));
        }

        // 3. Field lainnya
        $others = Arr::except($changes, ['assigned_to', 'condition']);

        if (! empty($others)) {
            $lines = [];

            foreach ($others as $field => $newValue) {
                $oldValue = $asset->getRawOriginal($field);
                $lines[] = sprintf(
                    '%s: %s menjadi %s',
                    self::FIELD_LABELS[$field] ?? $field,
                    $this->display($oldValue, $field),
                    $this->display($newValue, $field)
                );
            }

            $this->log($asset->id, 'Perubahan Data', implode('. ', $lines) . '.');
        }
    }

    /**
     * Dipicu untuk soft delete (Kotak Sampah) maupun force delete.
     */
    public function deleted(Asset $asset): void
    {
        $permanent = $asset->isForceDeleting();

        $this->log(
            $permanent ? null : $asset->id,
            'Penghapusan Aset',
            $permanent
                ? "Aset '{$asset->name}' (S/N: {$asset->asset_code}) telah dihapus permanen dari sistem."
                : "Aset '{$asset->name}' (S/N: {$asset->asset_code}) dipindahkan ke Kotak Sampah dan masih dapat dipulihkan."
        );
    }

    /**
     * Aset dipulihkan dari Kotak Sampah.
     */
    public function restored(Asset $asset): void
    {
        $this->log($asset->id, 'Perubahan Data', "Aset '{$asset->name}' (S/N: {$asset->asset_code}) dipulihkan dari Kotak Sampah.");
    }

    /**
     * File gambar hanya dibuang saat penghapusan permanen.
     */
    public function forceDeleted(Asset $asset): void
    {
        if ($asset->image) {
            Storage::disk('public')->delete($asset->image);
        }
    }

    /**
     * Simpan satu baris log jika ada user yang login.
     */
    private function log(?int $assetId, string $action, string $notes): void
    {
        if (! auth()->check()) {
            return;
        }

        AssetHistory::create([
            'asset_id' => $assetId,
            'user_id' => auth()->id(),
            'action' => $action,
            'notes' => $notes,
        ]);
    }

    private function display($value, string $field): string
    {
        if ($value === null || $value === '') {
            return '(kosong)';
        }

        if ($field === 'image') {
            return basename((string) $value);
        }

        return (string) $value;
    }
}
