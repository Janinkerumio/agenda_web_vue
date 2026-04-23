<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\ConcernController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

Route::middleware(['auth', 'verified'])->group(function () {

    Route::prefix('/app')->group(function () {

        Route::inertia('/dashboard', 'Dashboard' )->name('dashboard');

        Route::inertia('/create-agenda', 'Agenda/Create' )->name('agenda.create');
        Route::get('/view-agenda', [AgendaController::class, 'index'] )->name('agenda.view-all');
        Route::get('/view-agenda/{agenda_id}', [AgendaController::class, 'clickedAgenda'])->name('agenda.view');
        Route::get('/edit-agenda/{agenda_id}', [AgendaController::class, 'previewEditAgenda'])->name('agenda.edit-prev');

        Route::get('/concerns', [ConcernController::class, 'allConcerns'] )->name('concerns.all-concerns');
        Route::get('/agenda/{agenda_id}/raise-concern', [ConcernController::class, 'raiseConcern'])->name('concerns.create-preview');
        Route::get('/concerns/{concern_id}/edit', [ConcernController::class, 'editPreview'])->name('concerns.edit-preview');
        Route::get('/concerns/me', [ConcernController::class, 'yourConcerns'] )->name('concerns.my-concerns');
        Route::get('/concerns/{concern_id}/comments', [CommentController::class, 'index'])->name('concerns.comments');

        Route::inertia('/calendar', 'Calendar')->name('calendar');

        Route::inertia('/history', 'Archives/Reports')->name('archives.history');
        Route::inertia('/reports', 'Archives/History')->name('archives.reports');

        Route::inertia('/trash-agenda', 'Trash/Agendas' )->name('trash.agendas');
        Route::inertia('/trash-concern', 'Trash/Concerns' )->name('trash.concerns');

        Route::inertia('/users', 'People/Users' )->name('people');
        Route::get('/memberships', [UserController::class, 'showAllMemberRequests'])->name('memberships');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('settings.profile');
        
    });
});