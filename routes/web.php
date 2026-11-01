<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerOrderController;

Route::get('/', [StoreController::class, 'index'])->name('home');
Route::get('/san-pham/{product:slug}', [StoreController::class, 'show'])->name('products.show');
Route::middleware(['auth', 'can:customer'])->group(function () {
    Route::get('/gio-hang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/gio-hang/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/gio-hang', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/gio-hang/{key}', [CartController::class, 'destroy'])->name('cart.destroy');
});
Route::get('/dang-nhap', [AuthController::class, 'showLogin'])->middleware('guest')->name('login');
Route::post('/dang-nhap', [AuthController::class, 'login'])->middleware('guest');
Route::get('/dang-ky', [AuthController::class, 'showRegister'])->middleware('guest')->name('register');
Route::post('/dang-ky', [AuthController::class, 'register'])->middleware('guest');
Route::post('/dang-xuat', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/tai-khoan', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/tai-khoan', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'can:customer'])->group(function () {
    Route::get('/thanh-toan', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/thanh-toan', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/don-hang-cua-toi', [CustomerOrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/don-hang-cua-toi/{order}', [CustomerOrderController::class, 'show'])->name('customer.orders.show');
    Route::post('/don-hang-cua-toi/{order}/cancel', [CustomerOrderController::class, 'cancel'])->name('customer.orders.cancel');
});
Route::resource('categories', CategoryController::class)
    ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
    ->middleware(['auth', 'can:manage-categories']);

Route::middleware(['auth', 'can:manage-store'])->group(function () {
    Route::resource('products', ProductController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('orders', OrderController::class)
        ->only(['index', 'show', 'update']);
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});
