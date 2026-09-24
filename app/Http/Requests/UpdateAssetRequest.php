<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetRequest extends FormRequest
{
    /**
     * Otorisasi route sudah ditangani middleware can:admin/auth di routes/web.php
     * (edit & update memang boleh diakses semua role, seperti sebelumnya).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi pembaruan aset (dipindahkan dari AssetController::update).
     */
    public function rules(): array
    {
        return [
            'asset_code' => 'required|unique:assets,asset_code,' . $this->route('asset')->id,
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
