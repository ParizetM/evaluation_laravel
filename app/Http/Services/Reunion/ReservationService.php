<?php

namespace App\Http\Services\Reunion;

use App\Http\Repositories\Reunion\ReservationRepository;;
use App\Models\Reunion\Reservation;
use App\Models\Reunion\Salle;
use Auth;
use DB;
use Log;

class ReservationService
{
    /**
     * @var ReservationRepository
     */
    protected $repository;

    /**
     * Constructor
     * @param  ReservationRepository  $repository
     */
    public function __construct(ReservationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store a new model instance
     * @param  array<mixed>  $inputs
     * @return  Reservation
     */
    public function store(array $inputs): Reservation
    {
        $inputs['start_time'] = date('Y-m-d H:i:s', strtotime($inputs['reservation_date'].' '.$inputs['start_time']));
        $inputs['end_time'] = date('Y-m-d H:i:s', strtotime($inputs['reservation_date'].' '.$inputs['end_time']));
        $salle = Salle::find($inputs['salle_id']);
        if (!$salle) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'salle_id' => ['Salle introuvable.'],
            ]);
        }
        if (!$salle->isAvailableFor($inputs['start_time'], $inputs['end_time'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'reservation_date' => ['Salle déjà réservée.'],
            ]);
        }
        if (strtotime($inputs['end_time']) <= strtotime($inputs['start_time'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'end_time' => ['La date de fin doit être supérieure à la date de début.'],
            ]);
        }

        return $this->repository->store($inputs);
    }

    /**
     * Update the model instance
     * @param  Reservation  $reservation
     * @param  array<mixed>  $inputs
     * @return  Reservation
     */
    public function update(Reservation $reservation, array $inputs): Reservation
    {
        //
        // Règles de gestion à appliquer avant l'enregistrement en base
        //

        return $this->repository->update($reservation, $inputs);
    }

    /**
     * Delete the model instance
     * @param  Reservation  $reservation
     * @return bool|null
     */
    public function destroy(Reservation $reservation)
    {
        //
        // Règles de gestion à appliquer avant l'enregistrement en base
        //

        return $this->repository->destroy($reservation);
    }

    /**
     * Undelete the model instance
     * @param  Reservation  $reservation
     * @return void
     */


    /**
     * Return a JSON for index datatable
     * @return string|false|void — a JSON encoded string on success or FALSE on failure
     */
    public function json()
    {
        //
        // Règles de gestion à appliquer avant l'enregistrement en base
        //

        return $this->repository->json();
    }
}
