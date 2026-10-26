<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreController;

Route::get('/', [StoreController::class, 'index'])->name('home');
Route::get('/dang-nhap', [AuthController::class, 'showLogin'])->middleware('guest')->name('login');
Route::post('/dang-nhap', [AuthController::class, 'login'])->middleware('guest');
Route::post('/dang-xuat', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/tai-khoan', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/tai-khoan', [ProfileController::class, 'update'])->name('profile.update');
});
