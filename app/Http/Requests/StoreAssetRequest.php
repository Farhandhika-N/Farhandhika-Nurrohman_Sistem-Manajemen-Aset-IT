<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetRequest extends FormRequest
{
    /**
     * Otorisasi route sudah ditangani middleware can:admin di routes/web.php.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi pembuatan aset (dipindahkan dari AssetController::store).
     */
    public function rules(): array
    {
        return [
            'asset_code' => 'required|unique:assets',
            'name' => 'required',
            'category' => 'required',
            'condition' => 'required',
            'assigned_to' => 'nullable|string|max:255',
            'problem_description' => 'nullable|string|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    /**
     * Pesan validasi berbahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'asset_code.required' => 'Kode Aset (S/N) wajib diisi.',
            'asset_code.unique' => 'Kode Aset sudah terdaftar di sistem.',
            'name.required' => 'Nama / Merk barang wajib diisi.',
            'category.required' => 'Silakan pilih kategori.',
            'condition.required' => 'Silakan pilih kondisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'image.max' => 'Ukuran file gambar maksimal 2MB.',
        ];
    }
}
