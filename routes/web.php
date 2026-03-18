<?php

use App\Http\Controllers\Api\AuthentificationController;
use App\Http\Controllers\Api\V1\RegisterController;
use App\Http\Controllers\Api\V1\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/', function () {
   return view('welcome');
})->name('register');


Route::middleware('guest')->group(function () {

    //login
    Route::get('/login', [AuthentificationController::class,'index'])->name('login');
    Route::post('/login',[ AuthentificationController::class,'login']);
    //signup
    Route::get('/register', [RegisterController::class,'index'])->name('register');
    Route::post('/register',[ RegisterController::class,'create']);
    //forget password
    Route::get('/reset_password', [ResetPasswordController::class,'index'])->name('reset_password');
    Route::post('/reset_email_sent', [ResetPasswordController::class,'resetPassword'])->name('reset_email_sent');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
});

Route::view('/test','group.createGroup');
route::post('/logout',[ AuthentificationController::class,'logout'])->name('logout');
