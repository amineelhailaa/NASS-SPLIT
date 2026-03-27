<?php

use App\Http\Controllers\Api\Auth\AuthentificationController;
use App\Http\Controllers\Api\Auth\SocialAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//authentification
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirectToProvider']);
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'handleCallback']);
Route::post('/login',[AuthentificationController::class,'login']);
Route::post('/register',[AuthentificationController::class,'register']);
Route::post('/logout',[AuthentificationController::class,'logout']);










Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
