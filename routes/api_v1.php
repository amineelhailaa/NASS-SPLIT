<?php

use App\Http\Controllers\Api\V1\AdminController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ConversationController;
use App\Http\Controllers\Api\V1\ExpenseController;
use App\Http\Controllers\Api\V1\GroupController;
use App\Http\Controllers\Api\V1\InvitationController;
use App\Http\Controllers\Api\V1\MembershipController;
use App\Http\Controllers\Api\V1\MessageController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\ProfileController;

Route::middleware('auth:sanctum')->group(function () {

    // ===================//Admin//=======================//
    Route::middleware('can:admin')->group(function () {
        // categories:
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
        Route::patch('/categories/{category}', [CategoryController::class, 'update']);

        // users
        Route::patch('/users/{user}/ban', [AdminController::class, 'banUser']);
        Route::patch('/users/{user}/unban', [AdminController::class, 'unBanUser']);
    });

    // ===================//Owner//=======================//
    Route::middleware('can:owner,group')->group(function () {
        // invitation:
        Route::post('/groups/{group}/invite', [InvitationController::class, 'inviteEmail']);
        Route::get('/groups/{group}/invitation-code', [GroupController::class, 'invitationCode']);
        Route::get('/groups/{group}/invitations/pending', [InvitationController::class, 'pendingInvitations']);
        Route::patch('/groups/{group}/invitations/{invitation}/cancel', [InvitationController::class, 'cancelInvitation']);
        // members
        Route::patch('/groups/{group}/members/{membership}/kick', [MembershipController::class, 'kick']);
    });

    // ===================//Member or Owner//=======================//
    Route::middleware('can:member,group')->group(function () {
        Route::get('/groups/{group}/statistics', [GroupController::class, 'statistics']);

    });
    // invitation
    Route::post('/groups/join/{code}', [InvitationController::class, 'joinGroupByCode']);
    Route::post('/invitations/{token}/accept', [InvitationController::class, 'joinByInvitation']);
    Route::post('/invitations/{token}/decline', [InvitationController::class, 'declineInvitation']);
    Route::get('/invitations/{token}', [InvitationController::class, 'show']);

    // profile:
    Route::get('/profile', [ProfileController::class, 'edit']);
    Route::patch('/profile', [ProfileController::class, 'update']);

    // Group:
    Route::get('/groups', [GroupController::class, 'index']);
    Route::post('/groups', [GroupController::class, 'store']);
    Route::get('/groups/{id}', [GroupController::class, 'show']);
    Route::patch('/groups/{id}', [GroupController::class, 'update']);
    Route::delete('/groups/{id}', [GroupController::class, 'destroy']);
    Route::get('/groups/{group}/members', [GroupController::class, 'members']);
    Route::get('/groups/{group}/balance', [GroupController::class, 'myBalance']);
    Route::get('/groups/{group}/owes', [GroupController::class, 'owes']);

    // Expense:
    Route::get('/groups/{id}/expenses', [ExpenseController::class, 'index']);
    Route::post('/groups/{group}/expenses', [ExpenseController::class, 'store']);
    Route::get('/expenses/{id}', [ExpenseController::class, 'show']);
    Route::delete('/expenses/{id}', [ExpenseController::class, 'destroy']);

    // Pyament:
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::get('/payments/{id}', [PaymentController::class, 'show']);

    // conversation
    Route::get('/conversations', [ConversationController::class, 'index']);
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show']);

    // message
    Route::post('/conversations/{conversation}', [MessageController::class, 'store']);
    Route::delete('/messages/{message}', [MessageController::class, 'destroy']);

    // categories
    Route::get('/categories', [CategoryController::class, 'index']);

});
