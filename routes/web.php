<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/register');
});
Route::get('/products', [HomeController::class, 'products'])->name('products');
Route::get('/products/{id}', [HomeController::class, 'productDetail'])->name('product.detail');

/*
|--------------------------------------------------------------------------
| Guest (Register)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'submitForm']);
});

/*
|--------------------------------------------------------------------------
| Buyer Routes (Authenticated)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/cart', fn () => view('cart.index'))->name('cart.index');
    Route::get('/checkout', fn () => view('checkout.index'))->name('checkout.index');
});

/*
|--------------------------------------------------------------------------
| Seller Routes (Authenticated + Seller Middleware)
|--------------------------------------------------------------------------
*/

Route::prefix('seller')->middleware(['auth', 'seller'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('seller.dashboard');
    Route::get('/products', [DashboardController::class, 'products'])->name('seller.products');
    Route::get('/orders', [DashboardController::class, 'orders'])->name('seller.orders');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('seller.profile');
});

/*
|--------------------------------------------------------------------------
| Admin Route (Handled by Filament)
|--------------------------------------------------------------------------
*/

Route::get('/admin', fn () => redirect('/panel'));

/*
|--------------------------------------------------------------------------
| Authentication Routes (Login, Logout, etc.)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
