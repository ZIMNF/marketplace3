<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/panel');
});

Route::get('/home', function () {
    return redirect('/panel');
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
    Route::get('/dashboard', fn () => redirect('/panel'))->name('seller.dashboard');
    Route::get('/products', fn () => redirect('/panel'))->name('seller.products');
    Route::get('/orders', fn () => redirect('/panel'))->name('seller.orders');
    Route::get('/profile', fn () => redirect('/panel'))->name('seller.profile');
});

/*
|--------------------------------------------------------------------------
| Admin Route (Handled by Filament)
|--------------------------------------------------------------------------
*/

Route::get('/admin', fn () => redirect('/panel'));
Route::get('/panel', fn () => redirect('/panel/login'));

/*
|--------------------------------------------------------------------------
| Authentication Routes (Login, Logout, etc.)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
