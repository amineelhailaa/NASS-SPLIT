<?php

use App\Http\Controllers\Api\Auth\SocialAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

// OAuth callback must be on web middleware (needs session for Auth::login)
Route::get('/api/auth/{provider}/callback', [SocialAuthController::class, 'handleCallback']);

require __DIR__.'/auth.php';
