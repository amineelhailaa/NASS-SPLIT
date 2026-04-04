<?php


use App\Http\Controllers\Api\V1\MessageController;

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/conversation/{conversation}', [MessageController::class, 'store']);
    Route::delete('/conversation/{message}', [MessageController::class,'destroy']);
});
