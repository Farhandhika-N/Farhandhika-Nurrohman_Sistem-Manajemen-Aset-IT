<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Log Mutasi Aset</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10pt; color: #333; }
        
        /* --- KOP SURAT --- */
        .kop-surat { text-align: center; border-bottom: 3px solid #000; padding-bottom: 15px; margin-bottom: 25px; position: relative; }
        .kop-surat h1 { margin: 0; font-size: 18pt; letter-spacing: 1px; color: #1e293b; text-transform: uppercase; }
        .kop-surat h2 { margin: 5px 0; font-size: 14pt; color: #4f46e5; }
        .kop-surat p { margin: 0; font-size: 10pt; color: #475569; }
        .garis-tipis { border-bottom: 1px solid #000; margin-top: 2px; }

        /* --- JUDUL LAPORAN --- */
        .judul-laporan { text-align: center; margin-bottom: 20px; }
        .judul-laporan h3 { margin: 0; font-size: 14pt; text-decoration: underline; }
        .judul-laporan p { margin: 5px 0 0 0; font-size: 10pt; }

        /* --- TABEL --- */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #94a3b8; padding: 8px 10px; text-align: left; vertical-align: top; }
        th { background-color: #f1f5f9; color: #1e293b; font-size: 10pt; }
        td { font-size: 9pt; }
        
        .text-center { text-align: center; }
        
        /* --- FOOTER / TTD --- */
        .ttd-container { width: 100%; margin-top: 40px; }
        .ttd-box { width: 30%; float: right; text-align: center; }
        .clear { clear: both; }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <div class="kop-surat">
        <h1>PT. INTI PERSERO</h1>
        <h2>Divisi IT & Infrastruktur Jaringan</h2>
        <p>Jalan Moch. Toha No. 77, Bandung 40253, Jawa Barat</p>
        <p>Telp: (022) 1234-5678 | Email: it-support@intipersero.com</p>
        <div class="garis-tipis"></div>
    </div>

    <!-- JUDUL LAPORAN -->
    <div class="judul-laporan">
        <h3>LAPORAN MUTASI DAN AKTIVITAS ASET IT</h3>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y - H:i') }} WIB</p>
    </div>

    <!-- TABEL DATA -->
    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">Waktu</th>
                <th width="15%">Jenis Aksi</th>
                <th width="20%">Nama Aset (S/N)</th>
                <th width="30%">Detail Perubahan / Catatan</th>
                <th width="15%">Admin Pencatat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($histories as $index => $log)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <!-- Ditambahkan pengecekan apakah created_at ada nilainya -->
                    @if($log->created_at)
                        {{ $log->created_at->format('d/m/Y') }}<br>
                        <small>{{ $log->created_at->format('H:i') }} WIB</small>
                    @else
                        -
                    @endif
                </td>
                <td><strong>{{ $log->action }}</strong></td>
                <td>
                    <strong>{{ $log->asset->asset_code ?? 'N/A' }}</strong><br>
                    {{ $log->asset->name ?? 'Aset Terhapus' }}
                </td>
                <td>{{ $log->notes ?? '-' }}</td>
                <td>{{ $log->user->name ?? 'Sistem / Auto' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada data log aktivitas/mutasi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TEMPAT TANDA TANGAN -->
    <div class="ttd-container">
        <div class="ttd-box">
            <p>Bandung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p><strong>Kepala Divisi IT</strong></p>
            <br><br><br><br>
            <p style="text-decoration: underline; font-weight: bold;">{{ auth()->user()->name ?? 'Administrator IT' }}</p>
            <p>NIP. 19820311 200801 1 009</p>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>