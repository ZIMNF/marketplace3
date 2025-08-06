<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Authentication routes - semua menggunakan /panel
Route::prefix('panel')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    
    Route::get('register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('register', [RegisterController::class, 'submitForm']);
    
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

// Redirect root ke /panel
Route::get('/', function () {
    return redirect('/panel');
});

// Redirect auth ke /panel
Route::prefix('auth')->group(function () {
    Route::get('/', function () {
        return redirect('/panel');
    });
    
    Route::get('login', function () {
        return redirect('/panel/login');
    })->name('auth.login');
    
    Route::get('register', function () {
        return redirect('/panel/register');
    })->name('auth.register');
});
