<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asset;
use Carbon\Carbon;

class AssetSeeder extends Seeder
{
    public function run()
    {
        $assets = [
            // --- 10 DATA LAMA ---
            [
                'asset_code' => 'LPT-001',
                'name' => 'Lenovo ThinkPad T14 Gen 2',
                'category' => 'Laptop',
                'condition' => 'Baik',
                'problem_description' => null,
                'assigned_to' => 'Budi Santoso (Manager HR)',
                'created_at' => Carbon::now()->subDays(15),
            ],
            [
                'asset_code' => 'LPT-002',
                'name' => 'Dell Latitude 3420',
                'category' => 'Laptop',
                'condition' => 'Perbaikan',
                'problem_description' => 'Keyboard beberapa tombol tidak merespon, sedang menunggu sparepart pengganti.',
                'assigned_to' => null,
                'created_at' => Carbon::now()->subDays(14),
            ],
            [
                'asset_code' => 'LPT-003',
                'name' => 'MacBook Pro M1 2020',
                'category' => 'Laptop',
                'condition' => 'Baik',
                'problem_description' => null,
                'assigned_to' => 'Siti Aminah (Direktur Utama)',
                'created_at' => Carbon::now()->subDays(13),
            ],
            [
                'asset_code' => 'PCD-001',
                'name' => 'HP EliteDesk 800 G6',
                'category' => 'PC Desktop',
                'condition' => 'Baik',
                'problem_description' => null,
                'assigned_to' => 'Divisi Keuangan',
                'created_at' => Carbon::now()->subDays(12),
            ],
            [
                'asset_code' => 'PCD-002',
                'name' => 'Acer Veriton X',
                'category' => 'PC Desktop',
                'condition' => 'Rusak',
                'problem_description' => 'Motherboard terbakar akibat korsleting listrik. Diusulkan untuk afkir (pemutihan).',
                'assigned_to' => null,
                'created_at' => Carbon::now()->subDays(11),
            ],
            [
                'asset_code' => 'PCD-003',
                'name' => 'Dell OptiPlex 3090',
                'category' => 'PC Desktop',
                'condition' => 'Baik',
                'problem_description' => null,
                'assigned_to' => 'Divisi Marketing',
                'created_at' => Carbon::now()->subDays(10),
            ],
            [
                'asset_code' => 'PRN-001',
                'name' => 'Epson EcoTank L3210',
                'category' => 'Printer',
                'condition' => 'Baik',
                'problem_description' => null,
                'assigned_to' => 'Ruang Meeting A',
                'created_at' => Carbon::now()->subDays(9),
            ],
            [
                'asset_code' => 'PRN-002',
                'name' => 'HP LaserJet Pro M404n',
                'category' => 'Printer',
                'condition' => 'Perbaikan',
                'problem_description' => 'Roller penarik kertas aus (paper jam terus-menerus). Sedang diperbaiki oleh vendor.',
                'assigned_to' => null,
                'created_at' => Carbon::now()->subDays(8),
            ],
            [
                'asset_code' => 'RTR-001',
                'name' => 'MikroTik RouterBoard RB750Gr3',
                'category' => 'Router',
                'condition' => 'Baik',
                'problem_description' => null,
                'assigned_to' => 'Ruang Server 1',
                'created_at' => Carbon::now()->subDays(7),
            ],
            [
                'asset_code' => 'RTR-002',
                'name' => 'Cisco RV340 Dual WAN',
                'category' => 'Router',
                'condition' => 'Rusak',
                'problem_description' => 'Port WAN 1 mati tidak mendeteksi sinyal. Garansi sudah habis.',
                'assigned_to' => null,
                'created_at' => Carbon::now()->subDays(6),
            ],

            // --- 10 DATA BARU TAMBAHAN ---
            [
                'asset_code' => 'LPT-004',
                'name' => 'Asus ExpertBook B9',
                'category' => 'Laptop',
                'condition' => 'Baik',
                'problem_description' => null,
                'assigned_to' => 'Andi Wijaya (Manager Sales)',
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'asset_code' => 'LPT-005',
                'name' => 'HP ProBook 440 G8',
                'category' => 'Laptop',
                'condition' => 'Rusak',
                'problem_description' => 'Layar LCD retak karena jatuh, engsel patah, dan hardisk error membaca data.',
                'assigned_to' => null,
                'created_at' => Carbon::now()->subDays(4),
            ],
            [
                'asset_code' => 'LPT-006',
                'name' => 'ASUS ROG Zephyrus G14',
                'category' => 'Laptop',
                'condition' => 'Baik',
                'problem_description' => null,
                'assigned_to' => 'Rina (Desainer Grafis)',
                'created_at' => Carbon::now()->subDays(4),
            ],
            [
                'asset_code' => 'PCD-004',
                'name' => 'Lenovo ThinkCentre M70s',
                'category' => 'PC Desktop',
                'condition' => 'Baik',
                'problem_description' => null,
                'assigned_to' => 'Divisi IT Support',
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'asset_code' => 'PCD-005',
                'name' => 'Apple Mac Mini M2',
                'category' => 'PC Desktop',
                'condition' => 'Perbaikan',
                'problem_description' => 'Port HDMI sering kehilangan sinyal (blank screen). Dikirim ke service center resmi Apple.',
                'assigned_to' => null,
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'asset_code' => 'PCD-006',
                'name' => 'PC Rakitan Core i7 13700K',
                'category' => 'PC Desktop',
                'condition' => 'Baik',
                'problem_description' => null,
                'assigned_to' => 'Divisi Video Editor',
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'asset_code' => 'PRN-003',
                'name' => 'Brother DCP-T720DW',
                'category' => 'Printer',
                'condition' => 'Baik',
                'problem_description' => null,
                'assigned_to' => 'Meja Resepsionis',
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'asset_code' => 'PRN-004',
                'name' => 'Canon PIXMA G3010',
                'category' => 'Printer',
                'condition' => 'Rusak',
                'problem_description' => 'Head printer mampet total, tinta bocor hingga membasahi motherboard printer.',
                'assigned_to' => null,
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'asset_code' => 'RTR-003',
                'name' => 'Ubiquiti UniFi Dream Machine',
                'category' => 'Router',
                'condition' => 'Baik',
                'problem_description' => null,
                'assigned_to' => 'Lantai 2 - Coworking Space',
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'asset_code' => 'RTR-004',
                'name' => 'TP-Link Omada ER7206',
                'category' => 'Router',
                'condition' => 'Perbaikan',
                'problem_description' => 'Sering restart sendiri saat beban trafik tinggi atau overheat. Sedang dites di lab IT.',
                'assigned_to' => null,
                'created_at' => Carbon::now(),
            ],
        ];

        foreach ($assets as $asset) {
            Asset::create($asset);
        }
    }
}