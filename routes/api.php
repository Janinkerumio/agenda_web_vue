<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\ConcernController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    
    // Agenda API - /api/agendas
    Route::get('/agendas', [AgendaController::class, 'loadAgendas']);
    Route::get('/agendas/trashed', [AgendaController::class, 'trashed']);
    Route::get('/agendas/{id}', [AgendaController::class, 'show']);
    Route::post('/agendas', [AgendaController::class, 'store']);
    Route::put('/agendas/{id}', [AgendaController::class, 'update']);
    Route::delete('/agendas/{id}', [AgendaController::class, 'destroy']);
    Route::put('/agendas/{id}/restore', [AgendaController::class, 'restore']);
    Route::delete('/agendas/{id}/force-delete', [AgendaController::class, 'forceDelete']);

    // Concern API - /api/concerns
    Route::get('/concerns', [ConcernController::class, 'allConcerns']);
    Route::get('/concerns/my', [ConcernController::class, 'yourConcerns']);
    Route::get('/concerns/agenda/{agenda_id}', [ConcernController::class, 'loadConcernAg']);
    Route::get('/concerns/{id}', [ConcernController::class, 'show']);
    Route::post('/concerns', [ConcernController::class, 'store']);
    Route::put('/concerns/{id}', [ConcernController::class, 'update']);
    Route::delete('/concerns/{id}', [ConcernController::class, 'destroy']);
    Route::get('/concerns/trashed', [ConcernController::class, 'deletedConcerns']);
    Route::put('/concerns/{id}/restore', [ConcernController::class, 'restore']);
    Route::delete('/concerns/{id}/force-delete', [ConcernController::class, 'forceDelete']);

    // Comment API - /api/comments
    Route::get('/comments/{concern_id}', [CommentController::class, 'isCommentsLoad']);
    Route::post('/comments', [CommentController::class, 'store']);
    Route::get('/comments/{id}/edit', [CommentController::class, 'edit']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

    // User/Membership API
    Route::get('/membership-requests', [UserController::class, 'showAllMemberRequests']);
    Route::post('/submit-membership-requests', [UserController::class, 'storeRequest']);

    // Profile API
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
});