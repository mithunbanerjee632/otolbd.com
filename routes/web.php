<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;



Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/user-dashboard', [UserController::class, 'index'])->name('user.index');
});

Route::middleware(['auth',AuthAdmin::class])->group(function () {
    Route::get('/admin-dashboard', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/brands', [AdminController::class, 'brands'])->name('admin.brands');
    Route::get('/admin/brands/add', [AdminController::class, 'brands_ad'])->name('admin.brands.add');
    Route::post('/admin/brands/store', [AdminController::class, 'brand_store'])->name('admin.brands.store');
});

