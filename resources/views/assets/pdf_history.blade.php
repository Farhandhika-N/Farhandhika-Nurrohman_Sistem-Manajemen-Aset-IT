<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Log Mutasi Aset</title>
    <style>
        /* Pengaturan Dasar */
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 9pt; color: #334155; line-height: 1.4; }
        
        /* --- KOP SURAT BERSIH & TEGAS --- */
        .kop-surat { text-align: center; border-bottom: 2px solid #1e293b; padding-bottom: 12px; margin-bottom: 25px; }
        .kop-surat h1 { margin: 0 0 5px 0; font-size: 15pt; font-weight: bold; color: #0f172a; text-transform: uppercase; }
        .kop-surat h2 { margin: 0 0 5px 0; font-size: 12pt; color: #4f46e5; font-weight: bold; }
        .kop-surat p { margin: 2px 0; font-size: 9pt; color: #475569; }

        /* --- JUDUL LAPORAN --- */
        .judul-laporan { text-align: center; margin-bottom: 20px; }
        .judul-laporan h3 { margin: 0 0 5px 0; font-size: 12pt; font-weight: bold; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; }
        .judul-laporan p { margin: 0; font-size: 9pt; color: #64748b; }

        /* --- TABEL ENTERPRISE KELAS MENENGAH --- */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #94a3b8; padding: 7px 6px; text-align: left; vertical-align: top; word-wrap: break-word; }
        th { background-color: #f1f5f9; color: #1e293b; font-size: 9pt; font-weight: bold; border-bottom: 2px solid #475569; }
        
        td { font-size: 8.5pt; color: #1e293b; }
        
        .text-center { text-align: center; }
        .badge-hapus { color: #dc2626; font-weight: bold; }
        .text-muted { color: #64748b; font-size: 7.5pt; font-style: italic; }

        /* --- FOOTER / TANDA TANGAN --- */
        .ttd-container { width: 100%; margin-top: 40px; page-break-inside: avoid; }
        .ttd-box { width: 35%; float: right; text-align: center; }
        .ttd-box p { margin: 3px 0; color: #334155; font-size: 9pt; }
        .nama-ttd { font-weight: bold; text-decoration: underline; margin-top: 60px !important; color: #0f172a; }
        .clear { clear: both; }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <div class="kop-surat">
        <h1>PT Industri Telekomunikasi Indonesia (PERSERO)</h1>
        <h2>Divisi Bisnis dan Teknologi</h2>
        <p>Jalan Moch. Toha No. 77, Bandung 40253, Jawa Barat</p>
        <p>Telp: (022) 1234-5678 | Email: it-support@intipersero.com</p>
    </div>

    <!-- JUDUL LAPORAN -->
    <div class="judul-laporan">
        <h3>Laporan Mutasi dan Aktivitas Aset IT</h3>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y - H:i') }} WIB</p>
    </div>

    <!-- TABEL DATA -->
    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">Waktu</th>
                <th width="16%">Jenis Aksi</th>
                <th width="22%">Nama Aset (S/N)</th>
                <th width="27%">Detail Catatan</th>
                <th width="15%">Admin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($histories as $index => $log)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    @if($log->created_at)
                        {{ $log->created_at->format('d/m/Y') }}<br>
                        <span style="color: #64748b; font-size: 8pt;">{{ $log->created_at->format('H:i') }} WIB</span>
                    @else
                        -
                    @endif
                </td>
                <td><strong>{{ $log->action }}</strong></td>
                <td>
                    @if($log->asset)
                        <strong>{{ $log->asset->asset_code ?? 'N/A' }}</strong><br>
                        {{ $log->asset->name ?? 'Aset Terhapus' }}
                    @else
                        <span class="badge-hapus">[ Dihapus ]</span><br>
                        <span class="text-muted">Data fisik lenyap</span>
                    @endif
                </td>
                <td>{{ $log->notes ?? '-' }}</td>
                <td>{{ $log->user->name ?? 'Sistem / Auto' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 15px; color: #64748b;">Belum ada data log aktivitas atau mutasi yang tercatat dalam sistem.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TEMPAT TANDA TANGAN -->
    <div class="ttd-container">
        <div class="ttd-box">
            <p>Bandung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p><strong>Kepala Divisi IT</strong></p>
            <p class="nama-ttd">{{ auth()->user()->name ?? 'Administrator IT' }}</p>
            <p>NIP. 19820311 200801 1 009</p>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>