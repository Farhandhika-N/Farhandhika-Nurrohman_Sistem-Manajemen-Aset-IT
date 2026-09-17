<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AuthController;

Route::get('/', function () { 
    return redirect('/login'); 
});
Route::middleware('auth')->group(function () {
    Route::get('/assets/dashboard', [AssetController::class, 'dashboard'])->name('assets.dashboard');
    Route::get('/assets/export', [AssetController::class, 'exportExcel'])->name('assets.export');

    Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
    Route::post('/login', [AuthController::class, 'authenticate']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/assets-history', [AssetController::class, 'history'])->name('assets.history');
    Route::get('/assets-history/pdf', [AssetController::class, 'exportHistoryPDF'])->name('assets.history.pdf');

    Route::resource('assets', AssetController::class);
});