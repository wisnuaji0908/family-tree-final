<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\CoupleController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\ClaimPeopleMiddleware;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\CouplePeopleController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ParentsPeopleController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\AppSettingController;

    Route::get('/', function () {
        return redirect()->route('login');
    });

    // LOGIN
    Route::get('login', [LoginController::class, 'login'])->name('login');
    Route::post('login', [LoginController::class, 'store']);

    // REGISTER
    Route::get('register', [RegisterController::class, 'register'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);

    // OTP
    Route::post('/send-otp', [OtpController::class, 'sendOtp'])->name('send.otp');
    Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->name('verify.otp');

    // ROLE: ADMIN
    Route::middleware(['auth', 'can:admin', ClaimPeopleMiddleware::class])->group(function(){
        // ADMIN
        Route::resource('admin', AdminController::class);
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/admin/create', [AdminController::class, 'create'])->name('admin.create');
        Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store');
        Route::get('/admin/{id}', [AdminController::class, 'show'])->name('admin.show');
        Route::get('/admin/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
        Route::put('/admin/{id}', [AdminController::class, 'update'])->name('admin.update');
        Route::delete('/admin/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
      
        // PARENTS
        Route::resource('/parents', ParentsController::class);
        Route::get('/parent', [ParentsController::class, 'index'])->name('parent.index');

        // COUPLE
        Route::resource('couple', CoupleController::class);
        Route::get('/couple', [CoupleController::class, 'index'])->name('couple.index');
        Route::get('/couple-tree/{id}', [CoupleController::class, 'getTreeData'])->name('couple.tree');


    });

    // ROLE: PEOPLE
    Route::middleware(['auth','can:people', ClaimPeopleMiddleware::class])->group(function(){
        // PEOPLE
        Route::get('/people', [PeopleController::class, 'index'])->name('people.index');
        Route::get('/people/create', [PeopleController::class, 'create'])->name('people.create');
        Route::post('/people/store', [PeopleController::class, 'store'])->name('people.store');
        Route::get('/people/{id}/edit', [PeopleController::class, 'edit'])->name('people.edit');
        Route::put('/people/{id}', [PeopleController::class, 'update'])->name('people.update');
        Route::delete('/people/{id}', [PeopleController::class, 'destroy'])->name('people.destroy');
        
        // PARENTS
        Route::resource('/parentspeople', ParentsPeopleController::class);
        Route::get('/parentpeople', [ParentsPeopleController::class, 'index'])->name('peopleparents.index');

        // COUPLE
        Route::get('/couplepeople', [CouplePeopleController::class, 'index'])->name('peoplecouple.index');
        Route::get('/couplepeople/create', [CouplePeopleController::class, 'create'])->name('peoplecouple.create');
        Route::post('/couplepeople/store', [CouplePeopleController::class, 'store'])->name('peoplecouple.store');
        Route::get('/couplepeople/{couplesperson}/edit', [CouplePeopleController::class, 'edit'])->name('peoplecouple.edit');
        Route::put('/couplepeople/{couplesperson}', [CouplePeopleController::class, 'update'])->name('peoplecouple.update');
        Route::delete('/couplepeople/{couplesperson}', [CouplePeopleController::class, 'destroy'])->name('peoplecouple.destroy');
        Route::get('/couple-people-tree/{id}', [CouplePeopleController::class, 'getTreeData'])->name('couple.people.tree');

    });
    
    // AUTH 
    Route::middleware(['auth'])->group(function(){
        Route::post('logout', [LogoutController::class, 'logout'])->name('logout');

        Route::get('/people/claim', [PeopleController::class, 'showClaimForm'])->name('people.claim');
        Route::post('/people/claim', [PeopleController::class, 'claim'])->name('people.claim.process');
    });

    // FORGOT PASSWORD
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->middleware('guest')->name('password.request');
    
    Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('forgot-password');
    
    // RESET PASSWORD
    Route::get('/verify-otp', function () {
        return view('auth.verify-otp'); 
    })->middleware('guest')->name('otp.verify');
    
    Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->middleware('guest')->name('otp.verify.post');

    Route::get('/reset-password/{token}', function (string $token) {
        return view('auth.reset-password', ['token' => $token]);
    })->middleware('guest')->name('password.reset');

    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->middleware('guest')->name('password.store');

    Route::get('get-parent/{userId}', [ParentsController::class, 'getParent'])->name('get-parent');   

    // APP SETTING
    Route::resource('setting', App\Http\Controllers\AppSettingController::class);
    Route::get('/setting/{id?}', [AppSettingController::class, 'index'])->name('setting.index');
    Route::put('/setting/{setting}', [AppSettingController::class, 'update'])->name('setting.update');
