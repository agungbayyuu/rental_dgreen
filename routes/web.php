<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SewaController;

// Route::get('/', function () {
//     return view('welcome');
// });
// routes/web.php
Route::get('/sewa', [SewaController::class, 'create'])->name('sewa.create');
Route::post('/sewa', [SewaController::class, 'store'])->name('sewa.store');
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');