<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Redirect root & legacy paths
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect('/panel'));
Route::get('/home', fn () => redirect('/panel'));
Route::get('/admin', fn () => redirect('/panel'));

/*
|--------------------------------------------------------------------------
| Redirect legacy auth paths to /panel
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {
    Route::get('/', fn () => redirect('/panel'));
    Route::get('/login', fn () => redirect('/panel/login'));
    Route::get('/register', fn () => redirect('/panel/register'));
});

Route::get('/login', fn () => redirect('/panel/login'));
Route::get('/register', fn () => redirect('/panel/register'));

/*
|--------------------------------------------------------------------------
| Seller routes (redirect ke panel)
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
| Auth (Login/Logout) handled via custom controller
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
