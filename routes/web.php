<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ShopController;


Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product_slug}', [ShopController::class, 'product_details'])->name('shop.product.details');

Route::middleware(['auth'])->group(function () {
    Route::get('/user-dashboard', [UserController::class, 'index'])->name('user.index');
});

Route::middleware(['auth',AuthAdmin::class])->group(function () {
    Route::get('/admin-dashboard', [AdminController::class, 'index'])->name('admin.index');

    //admin panel Brands
    Route::get('/admin/brands', [AdminController::class, 'brands'])->name('admin.brands');
    Route::get('/admin/brands/add', [AdminController::class, 'brands_ad'])->name('admin.brands.add');
    Route::post('/admin/brands/store', [AdminController::class, 'brand_store'])->name('admin.brands.store');
    Route::get('/admin/brands/edit/{id}', [AdminController::class, 'brands_edit'])->name('admin.brands.edit');
    Route::post('/admin/brands/update', [AdminController::class, 'brands_update'])->name('admin.brands.update');
    Route::delete('/admin/brands/delete/{id}', [AdminController::class, 'brands_delete'])->name('admin.brands.delete');

    //admin panel Category

    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/admin/categories/add', [AdminController::class, 'categoriess_ad'])->name('admin.categories.add');
    Route::post('/admin/categories/store', [AdminController::class, 'category_store'])->name('admin.categories.store');
    Route::get('/admin/categories/edit/{id}', [AdminController::class, 'category_edit'])->name('admin.categories.edit');
    Route::post('/admin/categories/update', [AdminController::class, 'categories_update'])->name('admin.categories.update');
    Route::delete('/admin/categories/delete/{id}', [AdminController::class, 'categories_delete'])->name('admin.categories.delete');

    //products
    Route::get('/admin/products',[AdminController::class,'products'])->name('admin.products');
    Route::get('/admin/products/add',[AdminController::class,'products_add'])->name('admin.products.add');
    Route::post('/admin/products/store', [AdminController::class, 'products_store'])->name('admin.products.store');
    Route::get('/admin/products/edit/{id}', [AdminController::class, 'product_edit'])->name('admin.product.edit');
    Route::post('/admin/products/update', [AdminController::class, 'product_update'])->name('admin.products.update');
    Route::delete('/admin/products/delete/{id}', [AdminController::class, 'product_delete'])->name('admin.products.delete');






});


