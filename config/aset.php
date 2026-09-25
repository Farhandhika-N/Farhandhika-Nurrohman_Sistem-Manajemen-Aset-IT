<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Opsi Kategori Aset
    |--------------------------------------------------------------------------
    | Satu-satunya sumber daftar kategori. Dipakai oleh form tambah/edit,
    | dropdown filter, dan validasi (StoreAssetRequest / UpdateAssetRequest).
    | Tambah kategori baru cukup di sini.
    */

    'kategori' => [
        'Laptop' => 'Laptop',
        'PC Desktop' => 'PC Desktop',
        'Printer' => 'Printer',
        'Router' => 'Router',
    ],

    /*
    |--------------------------------------------------------------------------
    | Opsi Kondisi Aset
    |--------------------------------------------------------------------------
    | Key = nilai yang tersimpan di database, value = label tampilan.
    */

    'kondisi' => [
        'Baik' => 'Baik',
        'Perbaikan' => 'Sedang Perbaikan',
        'Rusak' => 'Rusak (Afkir)',
    ],

];
