<?php

namespace App\Http\Services\Reunion;

use App\Http\Repositories\Reunion\SalleRepository;;
use App\Models\Reunion\Salle;
use Auth;
use DB;
use Log;

class SalleService
{
    /**
     * @var SalleRepository
     */
    protected $repository;

    /**
     * Constructor
     * @param  SalleRepository  $repository
     */
    public function __construct(SalleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store a new model instance
     * @param  array<mixed>  $inputs
     * @return  Salle
     */
    public function store(array $inputs): Salle
    {
        //
        // Règles de gestion à appliquer avant l'enregistrement en base
        //

        return $this->repository->store($inputs);
    }

    /**
     * Update the model instance
     * @param  Salle  $salle
     * @param  array<mixed>  $inputs
     * @return  Salle
     */
    public function update(Salle $salle, array $inputs): Salle
    {
        //
        // Règles de gestion à appliquer avant l'enregistrement en base
        //

        return $this->repository->update($salle, $inputs);
    }

    /**
     * Delete the model instance
     * @param  Salle  $salle
     * @return bool|null
     */
    public function destroy(Salle $salle)
    {
        //
        // Règles de gestion à appliquer avant l'enregistrement en base
        //

        return $this->repository->destroy($salle);
    }

    /**
     * Undelete the model instance
     * @param  Salle  $salle
     * @return void
     */
    public function undelete(Salle $salle)
    {
        //
        // Règles de gestion à appliquer avant l'enregistrement en base
        //

        $this->repository->undelete($salle);
    }

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
