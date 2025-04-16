<?php

use App\Http\Controllers\Admin\ArchiveController;
use App\Http\Controllers\Admin\LieuController;
use App\Http\Controllers\Admin\SyntheseController;
use App\Http\Controllers\Conge\AbsenceController;
use App\Http\Controllers\Conge\MotifController;
use App\Http\Controllers\Felis\MaillardController;
use App\Http\Controllers\Planning\categorieController;
use App\Http\Controllers\Planning\PlanningController;
use App\Http\Controllers\Planning\TacheController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Models\Admin\Archive;
use App\Models\Admin\Lieu;
use App\Models\Conge\Absence;
use App\Models\Conge\Motif;
use App\Models\Planning\categorie;
use App\Models\Planning\Planning;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', [WelcomeController::class, 'index'])->name('home');

    //
    // Profil
    //
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/planning', PlanningController::class);
    Route::resource('/absence', AbsenceController::class);

    //
    // route model
    //
    Route::get('/model/{model_id}/undelete', [modelController::class, 'undelete'])->name('model.undelete');
    Route::bind('model_id', function ($model_id) {
        return model::onlyTrashed()->find($model_id);
    });
    Route::get('/model/json', [modelController::class, 'json'])->name('model.json');
    Route::resource('/model', modelController::class);
});

require __DIR__ . '/auth.php';
