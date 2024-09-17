<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ForgotPasswordController;


    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('login', [LoginController::class, 'login'])->name('login');
    Route::post('login', [LoginController::class, 'store']);

    Route::get('register', [RegisterController::class, 'register'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);

    Route::middleware(['auth'])->group(function(){
        Route::get('logout', [LogoutController::class, 'logout'])->name('logout');
        Route::post('logout', [LogoutController::class, 'logout']);
    });

    // forgot password
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->middleware('guest')->name('password.request');
    
    Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('forgot-password');
    
    Route::get('/reset-password/{token}', function (string $token) {
        return view('auth.reset-password', ['token' => $token]);
    })->middleware('guest')->name('password.reset');
    
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->middleware('guest')->name('password.store');
    
    // buat nyoba aja, jgn dianggap -----------------------------------------
    Route::resource('/admin', AdminController::class); 
    Route::resource('/parents', ParentsController::class);
    // ----------------------------------------------------------------------