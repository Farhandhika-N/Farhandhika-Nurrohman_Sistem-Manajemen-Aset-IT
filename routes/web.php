<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;

Route::get('/', function () { return redirect()->route('assets.index'); });
Route::resource('assets', AssetController::class);