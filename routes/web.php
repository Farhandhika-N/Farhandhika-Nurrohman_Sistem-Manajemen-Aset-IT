<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate'])->middleware(['guest', 'throttle:5,1']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Lupa & reset password (guest, dibatasi agar tidak disalahkan kirim email massal)
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->middleware('throttle:6,1')->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->middleware('throttle:6,1')->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/assets/dashboard', [AssetController::class, 'dashboard'])->name('assets.dashboard');
    Route::get('/assets/export', [AssetController::class, 'exportExcel'])->name('assets.export');
    Route::get('/assets/export-pdf', [AssetController::class, 'exportPDF'])->name('assets.export.pdf');

    Route::get('/assets-history', [AssetController::class, 'history'])->name('assets.history');
    Route::get('/assets-history/pdf', [AssetController::class, 'exportHistoryPDF'])->name('assets.history.pdf');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::middleware('can:admin')->group(function () {
        Route::get('/assets/trash', [AssetController::class, 'trash'])->name('assets.trash');
        Route::post('/assets/{asset}/restore', [AssetController::class, 'restore'])->withTrashed()->name('assets.restore');
        Route::delete('/assets/{asset}/force', [AssetController::class, 'forceDestroy'])->withTrashed()->name('assets.force');

        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::resource('assets', AssetController::class)
            ->only(['create', 'store', 'destroy']);
    });

    Route::resource('assets', AssetController::class)
        ->only(['index', 'show', 'edit', 'update']);
});
