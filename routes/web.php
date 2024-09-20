<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\CoupleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\ClaimPeopleMiddleware;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ForgotPasswordController;

    Route::get('/', function () {
        return redirect()->route('login');
    });

    // LOGIN
    Route::get('login', [LoginController::class, 'login'])->name('login');
    Route::post('login', [LoginController::class, 'store']);

    Route::get('register', [RegisterController::class, 'register'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);

    Route::middleware(['auth', ClaimPeopleMiddleware::class])->group(function(){
        Route::get('logout', [LogoutController::class, 'logout'])->name('logout');
        Route::post('logout', [LogoutController::class, 'logout']);

        // ADMIN
        Route::resource('admin', AdminController::class);
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/admin/create', [AdminController::class, 'create'])->name('admin.create');
        Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store');
        Route::get('/admin/{id}', [AdminController::class, 'show'])->name('admin.show');
        Route::get('/admin/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
        Route::put('/admin/{id}', [AdminController::class, 'update'])->name('admin.update');
        Route::delete('/admin/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
      
        // PEOPLE
        Route::get('/people', [PeopleController::class, 'index'])->name('people.index');
        Route::get('/people/create', [PeopleController::class, 'create'])->name('people.create');
        Route::post('/people/store', [PeopleController::class, 'store'])->name('people.store');
        Route::get('/people/{id}/edit', [PeopleController::class, 'edit'])->name('people.edit');
        Route::put('/people/{id}', [PeopleController::class, 'update'])->name('people.update');
        Route::delete('/people/{id}', [PeopleController::class, 'destroy'])->name('people.destroy');
        
        // PARENTS
        Route::resource('/parents', ParentsController::class);
        Route::get('/parent', [ParentsController::class, 'index'])->name('parent.index');

        // COUPLE
        Route::resource('couple', CoupleController::class);
        Route::get('/couple', [CoupleController::class, 'index'])->name('couple.index');
    });
    
    // AUTH 
    Route::middleware(['auth'])->group(function(){
        Route::get('/people/claim', [PeopleController::class, 'showClaimForm'])->name('people.claim');
        Route::post('/people/claim', [PeopleController::class, 'claim'])->name('people.claim.process');
    });

    // FORGOT PASSWORD
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->middleware('guest')->name('password.request');
    
    Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('forgot-password');
    
    // RESET PASSWORD
    Route::get('/reset-password/{token}', function (string $token) {
        return view('auth.reset-password', ['token' => $token]);
    })->middleware('guest')->name('password.reset');

    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->middleware('guest')->name('password.store');
   
