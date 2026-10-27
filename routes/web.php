<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CategoryController;

Route::get('/', [StoreController::class, 'index'])->name('home');
Route::get('/dang-nhap', [AuthController::class, 'showLogin'])->middleware('guest')->name('login');
Route::post('/dang-nhap', [AuthController::class, 'login'])->middleware('guest');
Route::get('/dang-ky', [AuthController::class, 'showRegister'])->middleware('guest')->name('register');
Route::post('/dang-ky', [AuthController::class, 'register'])->middleware('guest');
Route::post('/dang-xuat', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/tai-khoan', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/tai-khoan', [ProfileController::class, 'update'])->name('profile.update');
    
});
Route::resource('categories', CategoryController::class);
