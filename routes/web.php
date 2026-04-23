<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\ConcernController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register')
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/agendas/trashed', [AgendaController::class, 'trashed']);
    Route::get('/agendas/{id}', [AgendaController::class, 'show']);
    Route::post('/agendas/submit', [AgendaController::class, 'store']);
    Route::put('/agendas/{id}', [AgendaController::class, 'update']);
    Route::delete('/agendas/{id}', [AgendaController::class, 'destroy']);
    Route::put('/agendas/{id}/restore', [AgendaController::class, 'restore']);
    Route::delete('/agendas/{id}/force-delete', [AgendaController::class, 'forceDelete']);

    Route::get('/concerns', [ConcernController::class, 'allConcerns']);
    Route::get('/concerns/my', [ConcernController::class, 'yourConcerns']);
    Route::get('/concerns/agenda/{agenda_id}', [ConcernController::class, 'loadConcernAg']);
    Route::get('/concerns/{id}', [ConcernController::class, 'show']);
    Route::post('/concerns/submit', [ConcernController::class, 'store']);
    Route::put('/concerns/{id}', [ConcernController::class, 'update']);
    Route::delete('/concerns/{id}', [ConcernController::class, 'destroy']);
    Route::get('/concerns/trashed', [ConcernController::class, 'deletedConcerns']);
    Route::put('/concerns/{id}/restore', [ConcernController::class, 'restore']);
    Route::delete('/concerns/{id}/force-delete', [ConcernController::class, 'forceDelete']);

    Route::get('/comments/{concern_id}', [CommentController::class, 'isCommentsLoad']);
    Route::post('/comments', [CommentController::class, 'store']);
    Route::get('/comments/{id}/edit', [CommentController::class, 'edit']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

    Route::get('/membership-requests', [UserController::class, 'showAllMemberRequests']);
    Route::post('/submit-membership-requests', [UserController::class, 'storeRequest'])->name('submit-mbr-rqst');

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
});

require __DIR__.'/auth.php';
require __DIR__.'/inertia-pages.php';