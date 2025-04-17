<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Reunion\ReservationController;
use App\Http\Controllers\Reunion\SalleController;
use App\Http\Controllers\WelcomeController;

use App\Models\Reunion\Reservation;
use App\Models\Reunion\Salle;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', [WelcomeController::class, 'index'])->name('home');

    //
    // Profil
    //
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/salle/{salle_id}/undelete', [SalleController::class, 'undelete'])->name('salle.undelete');
    Route::bind('salle_id', function ($salle_id) {
        return Salle::onlyTrashed()->find($salle_id);
    });
    Route::get('/salle/json', [SalleController::class, 'json'])->name('salle.json');
    Route::resource('/salle', SalleController::class);
    Route::get('/reservation/{reservation_id}/undelete', [ReservationController::class, 'undelete'])->name('reservation.undelete');
    Route::bind('reservation_id', function ($reservation_id) {
        return Reservation::onlyTrashed()->find($reservation_id);
    });
    Route::get('/reservation/json', [ReservationController::class, 'json'])->name('reservation.json');
    Route::get('/reservation/mes-reservations', [ReservationController::class, 'mesReservations'])->name('reservation.mes_reservations');
    Route::resource('/reservation', ReservationController::class);
    Route::get('/api/check-availability', [ReservationController::class, 'checkAvailability'])->name('api.check-availability');

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
