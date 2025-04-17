<?php

namespace App\Http\Repositories\Reunion;

use App\Models\Reunion\Reservation;
use Auth;
use DB;
use Log;

class ReservationRepository
{
    /**
     * @var Reservation
     */
    protected $reservation;

    /**
     * Constructor
     * @param  Reservation  $reservation
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Save the model instance
     * @param  Reservation  $reservation
     * @return  Reservation
     */
    private function save(Reservation $reservation, array $inputs): Reservation
    {
        $reservation->user_id = Auth::id();
        $reservation->salle_id = $inputs['salle_id'];
        $reservation->start_time = $inputs['start_time'];
        $reservation->end_time = $inputs['end_time'];
        $reservation->save();

        return $reservation;
    }

    /**
     * Store a new model instance
     * @param  array<mixed>  $inputs
     * @return  Reservation
     */
    public function store(array $inputs): Reservation
    {
        $reservation = new $this->reservation;

        $this->save($reservation, $inputs);
        return $reservation;
    }

    /**
     * Update the model instance
     * @param  Reservation  $reservation
     * @param  array<mixed>  $inputs
     * @return  Reservation
     */
    public function update(Reservation $reservation, array $inputs): Reservation
    {
        $this->save($reservation, $inputs);
        return $reservation;
    }

    /**
     * Delete the model instance
     * @param  Reservation  $reservation
     * @return bool|null
     */
    public function destroy(Reservation $reservation)
    {

        return $reservation->delete();
    }

    /**
     * Undelete the model instance
     * @param  Reservation  $reservation
     * @return void
     */
    public function undelete(Reservation $reservation)
    {
        $reservation->restore();
    }

    /**
     * Return a JSON for index datatable
     * @return string|false|void — a JSON encoded string on success or FALSE on failure
     */
    public function json()
    {
        return json_encode(
            Reservation::all()
        );
    }
}
