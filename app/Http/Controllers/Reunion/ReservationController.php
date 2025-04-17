<?php

namespace App\Http\Controllers\Reunion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reunion\ReservationModelRequest;
use App\Http\Services\Reunion\ReservationService;
use App\Models\Reunion\Reservation;
use App\Models\Reunion\Salle;
use Auth;
use Carbon\Exceptions\InvalidFormatException;
use Carbon\Exceptions\NotLocaleAwareException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use InvalidArgumentException;
use Illuminate\Http\Request;
use Session;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Translation\Exception\InvalidArgumentException as ExceptionInvalidArgumentException;

class ReservationController extends Controller
{
    /**
     * @var ReservationService
     */
    private $service;
    private const ABILITY = 'reservation';
    private const PATH_VIEWS = 'reservation';

    /**
     * Constructor
     * @param   ReservationService $service
     */
    public function __construct(ReservationService $service)
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
        $user = Auth::user();
            $reservations = Reservation::with('salle')
                ->orderBy('start_time', 'asc')
                ->get();
        if ($this->can(self::ABILITY . '-retrieve')) {
            return view(self::PATH_VIEWS . '.index',
                [
                    'reservations' => $reservations,
                    'user' => $user,
                ]
            );
        }
    }
    /**
     * Display a listing of the current user's reservations.
     *
     * @return Response|RedirectResponse|View|void
     */
    public function mesReservations()
    {
        $user = Auth::user();
        $reservations = $user->reservations()
            ->with('salle')
            ->orderBy('start_time', 'asc')
            ->paginate();

            return view(self::PATH_VIEWS . '.mes-reservations',
                [
                    'reservations' => $reservations,
                    'user' => $user,
                ]
            );
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
    public function create(Request $request)
    {
        $salle_id = $request->query('salle');
        $salle_id = is_numeric($salle_id) ? (int)$salle_id : null;

        if ($this->can(self::ABILITY . '-create')) {
            $reservation = new Reservation();
            return $this->model($reservation, 'create', $salle_id);
        }

        return null;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  ReservationModelRequest  $request
     * @return RedirectResponse|void
     */
    public function store(ReservationModelRequest $request)
    {
        if ($this->can(self::ABILITY . '-create')) {
            $data = $request->all();
            $this->service->store($data);
            Session::put('ok', 'Création effectuée');

            return redirect(route(self::PATH_VIEWS . '.mes_reservations'));
        }
    }

    /**
     * @param Reservation $reservation
     * @return View|Factory|null
     * @throws BindingResolutionException
     * @throws RouteNotFoundException
     * @throws InvalidFormatException
     * @throws NotLocaleAwareException
     * @throws ExceptionInvalidArgumentException
     * @throws InvalidArgumentException
     */
    public function show(Reservation $reservation)
    {
        return $this->model($reservation, 'retrieve');
    }

    /**
     * @param Reservation $reservation
     * @return View|Factory|null
     * @throws BindingResolutionException
     * @throws RouteNotFoundException
     * @throws InvalidFormatException
     * @throws NotLocaleAwareException
     * @throws ExceptionInvalidArgumentException
     * @throws InvalidArgumentException
     */
    public function edit(Reservation $reservation)
    {
        return $this->model($reservation, 'update');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ReservationModelRequest  $request
     * @param  Reservation $reservation
     * @return RedirectResponse|void
     */
    public function update(ReservationModelRequest $request, Reservation $reservation)
    {
        if ($this->can(self::ABILITY . '-update')) {
            $this->service->update($reservation, $request->all());
            Session::put('ok', 'Mise à jour effectuée');

            return redirect(route(self::PATH_VIEWS . '.mes_reservations'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Reservation $reservation
     * @return RedirectResponse|void
     */
    public function destroy(Reservation $reservation)
    {
        if ($this->can(self::ABILITY . '-delete')) {
            $this->service->destroy($reservation);
            Session::put('ok', 'Suppression effectuée');

            return redirect(route(self::PATH_VIEWS . '.mes_reservations'))->with('success', 'Suppression effectuée');
        }
    }



    /**
     * Renvoie la liste des Reservation au format JSON pour leur gestion
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
     * @param Reservation $reservation|null
     * @param string $ability
     *
     * @return array<string, mixed>
     *
     */
    private function data(?Reservation $reservation, string $ability): array
    {
        return [
            'reservation' => $reservation,
            // variables � ajouter
            'disabled' => $ability === 'retrieve'
        ];
    }

    /**
     * @param Reservation $reservation|null
     * @param string $ability
     * @param int|null $salle_id
     * @return View|Factory|null
     * @throws BindingResolutionException
     * @throws RouteNotFoundException
     * @throws InvalidFormatException
     * @throws NotLocaleAwareException
     * @throws ExceptionInvalidArgumentException
     * @throws InvalidArgumentException
     */
    private function model(?Reservation $reservation, string $ability,$salle_id = null)
    {
        $salles = Salle::all();
        $salle = Salle::find($salle_id) ?? null;
        if ($this->can(self::ABILITY . '-' . $ability)) {
            return view(
                self::PATH_VIEWS . '.model',
                $this->data($reservation, $ability),
                [
                    'salles' => $salles,
                    'from_salle' => $salle,
                ]
            );
        }

        return null;
    }

    /**
     * Check the availability of a salle for a given time range.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkAvailability(\Illuminate\Http\Request $request)
    {
        $startTime = $request->query('start_time');
        $endTime = $request->query('end_time');

        if (!$startTime || !$endTime) {
            return response()->json(['error' => 'Invalid time range'], 400);
        }

        $availableSalles = Salle::whereDoesntHave('reservations', function ($query) use ($startTime, $endTime) {
            $query->where('start_time', '<', $endTime)
              ->where('end_time', '>', $startTime);
        })->pluck('id')->toArray();

        return response()->json(['available_salles' => $availableSalles]);
    }
}
