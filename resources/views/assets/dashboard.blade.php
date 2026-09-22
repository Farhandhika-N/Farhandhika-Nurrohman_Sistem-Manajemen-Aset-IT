@extends('assets.layout')
@section('content')

<style>
    body { background-color: #fafbfc; }
    .card-dashboard {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
        padding: 1.25rem;
        height: 100%;
    }
    
    .badge-soft-blue { background-color: #e0e7ff; color: #4338ca; font-weight: 600; padding: 0.35em 0.65em; border-radius: 6px; font-size: 0.7rem; }
    .badge-soft-green { background-color: #d1fae5; color: #059669; font-weight: 600; padding: 0.35em 0.65em; border-radius: 6px; font-size: 0.7rem; }
    .badge-soft-warning { background-color: #fef3c7; color: #d97706; font-weight: 600; padding: 0.35em 0.65em; border-radius: 6px; font-size: 0.7rem; }
    .badge-soft-danger { background-color: #fee2e2; color: #dc2626; font-weight: 600; padding: 0.35em 0.65em; border-radius: 6px; font-size: 0.7rem; }
    .badge-outline { border: 1px solid #e2e8f0; color: #64748b; font-weight: 600; padding: 0.35em 0.65em; border-radius: 6px; font-size: 0.7rem; background: #f8fafc; }

    .btn-primary-custom { background-color: #4f46e5; color: white; font-weight: 600; border-radius: 8px; font-size: 0.85rem; border: none; transition: 0.3s; }
    
    .btn-filter { background-color: #ffffff; color: #64748b !important; border: 1px solid #e2e8f0; font-weight: 600; border-radius: 6px; transition: 0.2s; }
    .btn-filter:hover { background-color: #f1f5f9; color: #0f172a !important; }
    .btn-filter.active-filter { background-color: #4f46e5 !important; color: #ffffff !important; border-color: #4f46e5 !important; }
    
    .kpi-title { font-size: 0.75rem; font-weight: 700; color: #64748b; letter-spacing: 0.5px; text-transform: uppercase; }
    .kpi-value { font-size: 2.2rem; font-weight: 800; color: #0f172a; line-height: 1; }
    .kpi-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
    
    .progress-kpi { height: 4px; border-radius: 4px; background-color: #f1f5f9; margin: 12px 0; overflow: visible; }
    .progress-bar-kpi { border-radius: 4px; }
    
    .table-custom th { font-size: 0.7rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; position: sticky; top: 0; background: white; z-index: 10; }
    .table-custom td { font-size: 0.85rem; color: #334155; vertical-align: middle; border-bottom: 1px solid #f8fafc; padding: 12px 8px; }
    
    .chart-center-wrapper { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; }

    /* Custom Scrollbar untuk tabel dan log aktivitas */
    .table-scrollable::-webkit-scrollbar { width: 6px; }
    .table-scrollable::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 8px; }
    .table-scrollable::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }

    /* CSS TIMELINE UNTUK DASHBOARD KONSISTEN DENGAN DETAIL ASET */
    .timeline {
        position: relative;
        padding-left: 28px;
        margin-bottom: 0;
        list-style: none;
        margin-top: 10px;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 11px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #e2e8f0;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 1.25rem;
    }
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    .timeline-icon {
        position: absolute;
        left: -28px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background-color: #fff;
        border: 2px solid #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
        box-shadow: 0 0 0 4px #ffffff;
    }
    .timeline-content {
        background-color: #f8fafc;
        border: 1px solid #f1f3f5;
        border-radius: 8px;
        padding: 12px;
        transition: 0.2s;
    }
    .timeline-content:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border-color: #e2e8f0;
    }
</style>

<!-- HEADER -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-bold mb-0" style="color: #0f172a;">Dashboard IT Asset Management</h4>
            <span class="badge-soft-blue text-uppercase">Overview</span>
        </div>
        <p class="text-muted small mb-0">Pantauan menyeluruh terhadap status dan kondisi inventaris aset perusahaan.</p>
    </div>
    @can('admin')
    <div class="mt-3 mt-md-0">
        <a href="{{ route('assets.create') }}" class="btn btn-primary-custom px-4 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Entri Aset Baru
        </a>
    </div>
    @endcan
</div>

<!-- ROW 1: KPI CARDS -->
<div class="row g-3 mb-4">
    <!-- Total Card -->
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card-dashboard d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="kpi-title">TOTAL INVENTARIS</div>
                <div class="kpi-icon" style="background: #eff6ff; color: #6366f1;"><i class="bi bi-box"></i></div>
            </div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="kpi-value">{{ $totalAset }}</div>
            </div>
            @php $newThisMonth = \App\Models\Asset::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(); @endphp
            <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                <span class="text-muted" style="font-size: 0.75rem;">Bulan ini</span>
                <span class="badge-soft-green"><i class="bi bi-arrow-up-short"></i> +{{ $newThisMonth }} Aset Baru</span>
            </div>
        </div>
    </div>

    <!-- Kondisi Baik -->
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card-dashboard d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="kpi-title">KONDISI BAIK</div>
                <div class="kpi-icon" style="background: #ecfdf5; color: #10b981;"><i class="bi bi-check-circle"></i></div>
            </div>
            <div class="d-flex justify-content-between align-items-end mb-2">
                <div>
                    <span class="kpi-value" style="color: #10b981;">{{ $asetBaik }}</span>
                    <span class="fw-semibold text-muted" style="font-size: 0.85rem;">Unit</span>
                </div>
                <span class="fw-bold text-success" style="font-size: 0.75rem;">{{ $totalAset > 0 ? round(($asetBaik/$totalAset)*100, 1) : 0 }}% Rasio</span>
            </div>
            <div class="progress progress-kpi mb-3">
                <div class="progress-bar progress-bar-kpi" style="width: {{ $totalAset > 0 ? ($asetBaik/$totalAset)*100 : 0 }}%; background: #10b981;"></div>
            </div>
            @php $baikThisMonth = \App\Models\Asset::where('condition', 'Baik')->whereMonth('updated_at', now()->month)->count(); @endphp
            <div class="mt-auto d-flex justify-content-between align-items-center">
                <span class="text-muted" style="font-size: 0.7rem;">Siap digunakan</span>
                <span class="fw-bold" style="font-size: 0.7rem; color: #10b981;">+{{ $baikThisMonth }} Update</span>
            </div>
        </div>
    </div>

    <!-- Dalam Perbaikan -->
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card-dashboard d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="kpi-title">DALAM PERBAIKAN</div>
                <div class="kpi-icon" style="background: #fffbeb; color: #f59e0b;"><i class="bi bi-tools"></i></div>
            </div>
            <div class="d-flex justify-content-between align-items-end mb-2">
                <div>
                    <span class="kpi-value" style="color: #f59e0b;">{{ $asetPerbaikan }}</span>
                    <span class="fw-semibold text-muted" style="font-size: 0.85rem;">Unit</span>
                </div>
            </div>
            <div class="progress progress-kpi mb-3">
                <div class="progress-bar progress-bar-kpi" style="width: {{ $totalAset > 0 ? ($asetPerbaikan/$totalAset)*100 : 0 }}%; background: #f59e0b;"></div>
            </div>
            @php $perbaikanThisMonth = \App\Models\Asset::where('condition', 'Perbaikan')->whereMonth('updated_at', now()->month)->count(); @endphp
            <div class="mt-auto d-flex justify-content-between align-items-center">
                <span class="text-muted" style="font-size: 0.7rem;">Ditangani teknisi</span>
                <span class="fw-bold" style="font-size: 0.7rem; color: #f59e0b;">+{{ $perbaikanThisMonth }} Update</span>
            </div>
        </div>
    </div>

    <!-- Kondisi Rusak -->
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card-dashboard d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="kpi-title">KONDISI RUSAK (AFKIR)</div>
                <div class="kpi-icon" style="background: #fef2f2; color: #ef4444;"><i class="bi bi-x-octagon"></i></div>
            </div>
            <div class="d-flex justify-content-between align-items-end mb-2">
                <div>
                    <span class="kpi-value" style="color: #ef4444;">{{ $asetRusak }}</span>
                    <span class="fw-semibold text-muted" style="font-size: 0.85rem;">Unit</span>
                </div>
            </div>
            <div class="progress progress-kpi mb-3">
                <div class="progress-bar progress-bar-kpi" style="width: {{ $totalAset > 0 ? ($asetRusak/$totalAset)*100 : 0 }}%; background: #ef4444;"></div>
            </div>
            @php $rusakThisMonth = \App\Models\Asset::where('condition', 'Rusak')->whereMonth('updated_at', now()->month)->count(); @endphp
            <div class="mt-auto d-flex justify-content-between align-items-center">
                <span class="text-muted" style="font-size: 0.7rem;">Menunggu afkir</span>
                <span class="fw-bold" style="font-size: 0.7rem; color: #ef4444;">+{{ $rusakThisMonth }} Update</span>
            </div>
        </div>
    </div>
</div>

<!-- ROW 2: ALOKASI KATEGORI (BAR CHART) & RASIO KESEHATAN -->
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card-dashboard d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h6 class="fw-bold mb-0 text-dark">Alokasi & Distribusi Aset Berdasarkan Kategori</h6>
                    </div>
                    <p class="text-muted small mt-1 mb-0">Visualisasi jumlah unit berdasarkan jenis perangkat</p>
                </div>
                <span class="badge-outline">Total {{ count($kategoriLabel) }} Kategori</span>
            </div>
            <div style="position: relative; height: 220px; width: 100%; display: flex; justify-content: center; flex-grow: 1;">
                <canvas id="categoryBarChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-dashboard d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h6 class="fw-bold mb-0 text-dark">Rasio Kesehatan Aset</h6>
                    <p class="text-muted mb-0" style="font-size: 0.75rem;">Status kelayakan unit</p>
                </div>
            </div>
            <div style="position: relative; height: 180px; width: 100%; display: flex; justify-content: center;">
                <canvas id="conditionChart"></canvas>
                <div class="chart-center-wrapper">
                    <h2 class="fw-bolder mb-0" style="color: #0f172a; font-size: 2rem;">{{ $totalAset > 0 ? round(($asetBaik/$totalAset)*100) : 0 }}%</h2>
                    <div style="font-size: 0.6rem; font-weight: 700; color: #64748b; letter-spacing: 1px;">OPERASIONAL</div>
                    <div style="font-size: 0.65rem; color: #64748b;">{{ $asetBaik }} / {{ $totalAset }} Unit</div>
                </div>
            </div>
            <div class="row g-2 mt-4 px-2 justify-content-center">
                <div class="col-12 col-sm-4">
                    <div class="border rounded px-2 py-1 text-center" style="font-size: 0.7rem; background: #fafbfc;">
                        <span class="fw-semibold text-muted d-block mb-1"><i class="bi bi-circle-fill" style="color: #10b981;"></i> Baik</span>
                        <span class="fw-bold">{{ $totalAset > 0 ? round(($asetBaik/$totalAset)*100) : 0 }}%</span>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="border rounded px-2 py-1 text-center" style="font-size: 0.7rem; background: #fafbfc;">
                        <span class="fw-semibold text-muted d-block mb-1"><i class="bi bi-circle-fill" style="color: #f59e0b;"></i> Perbaikan</span>
                        <span class="fw-bold">{{ $totalAset > 0 ? round(($asetPerbaikan/$totalAset)*100) : 0 }}%</span>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="border rounded px-2 py-1 text-center" style="font-size: 0.7rem; background: #fafbfc;">
                        <span class="fw-semibold text-muted d-block mb-1"><i class="bi bi-circle-fill" style="color: #ef4444;"></i> Rusak</span>
                        <span class="fw-bold">{{ $totalAset > 0 ? round(($asetRusak/$totalAset)*100) : 0 }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ROW 3: TABEL & LOG AKTIVITAS -->
<div class="row g-3">
    <!-- Tabel Seluruh Aset -->
    <div class="col-lg-8">
        <div class="card-dashboard">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
                <div>
                    <h6 class="fw-bold mb-1 text-dark">Direktori Seluruh Aset</h6>
                    <p class="text-muted small mb-0 mt-1">Data lengkap inventaris terbaru</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted d-none d-sm-inline" style="font-size: 0.75rem;">Filter:</span>
                    <div class="btn-group" id="filter-buttons">
                        <button type="button" class="btn btn-sm btn-filter active-filter px-2 px-sm-3" data-filter="all" style="font-size: 0.75rem;">Semua</button>
                        <button type="button" class="btn btn-sm btn-filter px-2 px-sm-3" data-filter="Baik" style="font-size: 0.75rem;">Baik</button>
                        <button type="button" class="btn btn-sm btn-filter px-2 px-sm-3" data-filter="Perbaikan" style="font-size: 0.75rem;">Perbaikan</button>
                        <button type="button" class="btn btn-sm btn-filter px-2 px-sm-3" data-filter="Rusak" style="font-size: 0.75rem;">Rusak</button>
                    </div>
                </div>
            </div>
            
            @php 
                $semuaAset = \App\Models\Asset::latest()->get(); 
            @endphp
            
            <div class="table-responsive table-scrollable" style="max-height: 350px; overflow-y: auto;">
                <table class="table table-custom w-100 m-0">
                    <thead>
                        <tr>
                            <th>KODE / S/N</th>
                            <th>ITEM BARANG</th>
                            <th>KATEGORI & PEMAKAI</th>
                            <th>STATUS KONDISI</th>
                        </tr>
                    </thead>
                    <tbody id="asset-table-body">
                        @forelse ($semuaAset as $asset)
                        <tr class="asset-row" data-condition="{{ $asset->condition }}">
                            <td>
                                <div class="fw-bold" style="color: #4f46e5; font-family: monospace;">{{ $asset->asset_code }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $asset->name }}</div>
                                <div class="text-muted" style="font-size: 0.7rem;">Tgl Input: {{ $asset->created_at->format('d M Y') }}</div>
                            </td>
                            <td>
                                <div class="text-dark" style="font-size: 0.85rem;">{{ $asset->category }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-person-fill"></i> {{ $asset->assigned_to ?? 'Gudang (Tersedia)' }}</div>
                            </td>
                            <td>
                                @if($asset->condition == 'Baik')
                                    <span class="text-success fw-bold"><i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i> Baik</span>
                                @elseif($asset->condition == 'Rusak')
                                    <span class="text-danger fw-bold"><i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i> Rusak</span>
                                @else
                                    <span class="text-warning fw-bold"><i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i> Perbaikan</span>
                                @endif
                                
                                @if($asset->problem_description)
                                    <div class="text-muted mt-1 text-truncate" style="font-size: 0.65rem; max-width: 150px;" title="{{ $asset->problem_description }}">
                                        Kendala: {{ $asset->problem_description }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr id="empty-row">
                            <td colspan="4" class="text-center text-muted py-4">Belum ada data aset yang terdaftar.</td>
                        </tr>
                        @endforelse
                        <tr id="no-match-row" style="display: none;">
                            <td colspan="4" class="text-center text-muted py-5">
                                <i class="bi bi-search fs-3 d-block mb-2 text-light"></i>
                                Data dengan kondisi tersebut tidak ditemukan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Log Aktivitas Terbaru dengan TIMELINE -->
    <div class="col-lg-4">
        <div class="card-dashboard d-flex flex-column h-100">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h6 class="fw-bold mb-0 text-dark">Log Mutasi & Aktivitas</h6>
                    <p class="text-muted mb-0" style="font-size: 0.75rem;">Jejak audit terbaru</p>
                </div>
                <a href="{{ route('assets.history') }}" class="text-decoration-none fw-semibold" style="font-size: 0.75rem; color: #4f46e5;">Lihat Semua &rarr;</a>
            </div>

            <!-- Menambahkan class table-scrollable -->
            <div class="table-responsive table-scrollable flex-grow-1 pe-2" style="max-height: 350px; overflow-y: auto;">
                @if(isset($recentHistories) && $recentHistories->count() > 0)
                    <ul class="timeline">
                        @foreach($recentHistories as $log)
                            <li class="timeline-item">
                                <!-- Ikon Dinamis berdasarkan Jenis Aksi -->
                                <div class="timeline-icon" style="color: #4f46e5; border-color: #4f46e5;">
                                    @if($log->action == 'Registrasi Aset Baru')
                                        <i class="bi bi-plus text-success" style="font-size: 1.2rem;"></i>
                                    @elseif($log->action == 'Mutasi Pemakai')
                                        <i class="bi bi-arrow-left-right text-primary" style="font-size: 0.85rem;"></i>
                                    @elseif($log->action == 'Penghapusan Aset')
                                        <i class="bi bi-trash text-danger" style="font-size: 0.8rem;"></i>
                                    @else
                                        <i class="bi bi-wrench text-warning" style="font-size: 0.8rem;"></i>
                                    @endif
                                </div>
                                
                                <!-- Konten Timeline -->
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-center mb-1 gap-2">
                                        <span class="fw-bold text-dark text-truncate" style="font-size: 0.8rem; max-width: 140px;">{{ $log->action }}</span>
                                        <span class="text-muted fw-medium" style="font-size: 0.65rem;"><i class="bi bi-clock me-1"></i>{{ $log->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="text-secondary mb-1" style="font-size: 0.75rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="{{ $log->notes }}">
                                        {{ $log->notes }}
                                    </div>
                                    <div class="text-muted fw-medium mt-2" style="font-size: 0.7rem;">
                                        <i class="bi bi-upc-scan me-1" style="color: #4f46e5;"></i> {{ $log->asset->asset_code ?? 'Aset Terhapus' }}
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="d-flex flex-column align-items-center justify-content-center h-100 py-5 text-muted">
                        <i class="bi bi-journal-x fs-2 d-block mb-2" style="color: #cbd5e1;"></i>
                        <span style="font-size: 0.8rem; font-weight: 500;">Belum ada aktivitas.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Load library Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        const ctxCategory = document.getElementById('categoryBarChart').getContext('2d');
        new Chart(ctxCategory, {
            type: 'bar',
            data: {
                labels: {!! json_encode($kategoriLabel) !!},
                datasets: [{
                    label: 'Jumlah Unit',
                    data: {!! json_encode($kategoriData) !!},
                    backgroundColor: ['#4f46e5', '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                    borderRadius: 6,
                    borderWidth: 0,
                    barPercentage: 0.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#64748b' },
                        grid: { color: '#f1f5f9', drawBorder: false }
                    },
                    x: {
                        ticks: { color: '#64748b', font: {size: 11} },
                        grid: { display: false, drawBorder: false }
                    }
                }
            }
        });

        const ctxCondition = document.getElementById('conditionChart').getContext('2d');
        new Chart(ctxCondition, {
            type: 'doughnut',
            data: {
                labels: ['Baik', 'Perbaikan', 'Rusak'],
                datasets: [{
                    data: [{{ $asetBaik }}, {{ $asetPerbaikan }}, {{ $asetRusak }}],
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '82%',
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: true }
                }
            }
        });

        const filterButtons = document.querySelectorAll('.btn-filter');
        const assetRows = document.querySelectorAll('.asset-row');
        const noMatchRow = document.getElementById('no-match-row');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                filterButtons.forEach(btn => btn.classList.remove('active-filter'));
                this.classList.add('active-filter');

                const filterValue = this.getAttribute('data-filter');
                let matchCount = 0;

                assetRows.forEach(row => {
                    const rowCondition = row.getAttribute('data-condition');
                    
                    if (filterValue === 'all' || rowCondition === filterValue) {
                        row.style.display = ''; 
                        matchCount++;
                    } else {
                        row.style.display = 'none'; 
                    }
                });

                if(matchCount === 0 && assetRows.length > 0) {
                    noMatchRow.style.display = '';
                } else if (noMatchRow) {
                    noMatchRow.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection