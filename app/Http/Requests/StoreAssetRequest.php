<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'category' => ['required', Rule::in(array_keys(config('aset.kategori')))],
            'condition' => ['required', Rule::in(array_keys(config('aset.kondisi')))],
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
            'category.in' => 'Kategori yang dipilih tidak valid.',
            'condition.required' => 'Silakan pilih kondisi.',
            'condition.in' => 'Kondisi yang dipilih tidak valid.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'image.max' => 'Ukuran file gambar maksimal 2MB.',
        ];
    }
}
