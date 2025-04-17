<?php

namespace App\Http\Controllers\Reunion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reunion\SalleModelRequest;
use App\Http\Services\Reunion\SalleService;
use App\Models\Reunion\Salle;
use Carbon\Exceptions\InvalidFormatException;
use Carbon\Exceptions\NotLocaleAwareException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use InvalidArgumentException;
use Session;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Translation\Exception\InvalidArgumentException as ExceptionInvalidArgumentException;

class SalleController extends Controller
{
    /**
     * @var SalleService
     */
    private $service;
    private const ABILITY = 'salle';
    private const PATH_VIEWS = 'salle';

    /**
     * Constructor
     * @param   SalleService $service
     */
    public function __construct(SalleService $service)
    {
        $this->middleware('auth');
        $this->service = $service;
        Session::put('level_menu_1', 'Reunions');
        Session::put('level_menu_2', self::ABILITY);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response|RedirectResponse|View|void
     */
    public function index()
    {
        $salles = Salle::all();
        if ($this->can(self::ABILITY . '-retrieve')) {
            return view(self::PATH_VIEWS . '.index', [
                'salles' => $salles,
                ]);
        }
    }

    /**
     * @return View|Factory|null
     * @throws BindingResolutionException
     * @throws RouteNotFoundException
     * @throws InvalidFormatException
     * @throws NotLocaleAwareException
     * @throws ExceptionInvalidArgumentException
     * @throws InvalidArgumentException
     */
    public function create()
    {
        return $this->model(null, 'create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SalleModelRequest  $request
     * @return RedirectResponse|void
     */
    public function store(SalleModelRequest $request)
    {
        if ($this->can(self::ABILITY . '-create')) {
            $data = $request->all();

            $salle = $this->service->store($data);
            Session::put('ok', 'Création effectuée');

            return redirect(self::PATH_VIEWS);
        }
    }

    /**
     * @param Salle $salle
     * @return View|Factory|null
     * @throws BindingResolutionException
     * @throws RouteNotFoundException
     * @throws InvalidFormatException
     * @throws NotLocaleAwareException
     * @throws ExceptionInvalidArgumentException
     * @throws InvalidArgumentException
     */
    public function show(Salle $salle)
    {
        return $this->model($salle, 'retrieve');
    }

    /**
     * @param Salle $salle
     * @return View|Factory|null
     * @throws BindingResolutionException
     * @throws RouteNotFoundException
     * @throws InvalidFormatException
     * @throws NotLocaleAwareException
     * @throws ExceptionInvalidArgumentException
     * @throws InvalidArgumentException
     */
    public function edit(Salle $salle)
    {
        return $this->model($salle, 'update');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SalleModelRequest  $request
     * @param  Salle $salle
     * @return RedirectResponse|void
     */
    public function update(SalleModelRequest $request, Salle $salle)
    {
        if ($this->can(self::ABILITY . '-update')) {
            $this->service->update($salle, $request->all());
            Session::put('ok', 'Mise à jour effectuée');

            return redirect(route(self::PATH_VIEWS . '.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Salle $salle
     * @return RedirectResponse|void
     */
    public function destroy(Salle $salle)
    {
        if ($this->can(self::ABILITY . '-delete')) {
            $this->service->destroy($salle);
            Session::put('ok', 'Suppression effectuée');

            return redirect(route(self::PATH_VIEWS . '.index'));
        }
    }

    /**
     * Restaure un �l�ment supprim�
     *
     * @example Penser � utiliser un bind dans le web.php
     *          Route::bind('salle_id', function ($salle_id) {
     *              return Salle::onlyTrashed()->find($salle_id);
     *          });
     * @param  Salle $salle
     * @return RedirectResponse|void
     */
    public function undelete(Salle $salle)
    {
        if ($this->can(self::ABILITY . '-delete')) {
            $this->service->undelete($salle);
            Session::put('ok', 'Restauration effectuée');

            return redirect(route(self::PATH_VIEWS . '.index'));
        }
    }

    /**
     * Renvoie la liste des Salle au format JSON pour leur gestion
     * @return string|false|void � a JSON encoded string on success or FALSE on failure
     */
    public function json()
    {
        if ($this->can(self::ABILITY . '-retrieve')) {
            return $this->service->json();
        }
    }

    /**
     * Rempli un tableau avec les données nécessaires aux vues
     *
     * @param Salle $salle|null
     * @param string $ability
     *
     * @return array<string, mixed>
     *
     * @throws InvalidArgumentException
     */
    private function data(?Salle $salle, string $ability): array
    {
        return [
            'salle' => $salle,
            // variables � ajouter
            'disabled' => $ability === 'retrieve'
        ];
    }

    /**
     * @param Salle $salle|null
     * @param string $ability
     * @return View|Factory|null
     * @throws BindingResolutionException
     * @throws RouteNotFoundException
     * @throws InvalidFormatException
     * @throws NotLocaleAwareException
     * @throws ExceptionInvalidArgumentException
     * @throws InvalidArgumentException
     */
    private function model(?Salle $salle, string $ability)
    {
        if ($this->can(self::ABILITY . '-' . $ability)) {
            return view(
                self::PATH_VIEWS . '.model',
                $this->data($salle, $ability)
            );
        }

        return null;
    }
}
