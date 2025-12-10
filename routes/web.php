<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\InstrumentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [InstrumentController::class, 'index'])->name('home');
Route::get('/catalog', [InstrumentController::class, 'index'])->name('catalog');
Route::get('/instruments/{instrument:slug}', [InstrumentController::class, 'show'])->name('instruments.show');

// Cart & checkout
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{instrument}', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{instrument}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{instrument}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/coupon', [CartController::class, 'apply'])->name('cart.coupon.apply');
Route::post('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

Route::middleware('auth')->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{instrument}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{instrument}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('profile.edit');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::resource('instruments', InstrumentController::class)->except(['show']);
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('brands', BrandController::class)->except(['show']);
        Route::resource('orders', OrderAdminController::class)->only(['index', 'show', 'update']);
    });

require __DIR__.'/auth.php';
