<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\DashboardController;

// Rute utama sekarang mengarah ke Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/assets/export', [AssetController::class, 'exportExcel'])->name('assets.export');
Route::resource('assets', AssetController::class);