<?php

use App\Http\Controllers\Api\V1\MessageController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/conversations/{conversation}', [MessageController::class, 'store']);
    Route::delete('/messages/{message}', [MessageController::class, 'destroy']);
});
