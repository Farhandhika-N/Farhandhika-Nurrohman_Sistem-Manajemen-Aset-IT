<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AuthController;

Route::get('/', function () { 
    return redirect('/login'); 
});

Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/assets/dashboard', [AssetController::class, 'dashboard'])->name('assets.dashboard');
    Route::get('/assets/export', [AssetController::class, 'exportExcel'])->name('assets.export');
    Route::resource('assets', AssetController::class);
});