<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;

// Authentication routes
Route::prefix('auth')->group(function () {
    Route::get('login', function () {
        return view('auth.login');
    })->name('login');
    
    Route::post('login', function () {
        return 'Login logic here';
    });
    
    Route::get('register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('register', [RegisterController::class, 'submitForm']);
    
    Route::post('logout', function () {
        return 'Logout logic here';
    })->name('logout');
});
