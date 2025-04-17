<?php

namespace App\Http\Controllers;

use App\Models\Reunion\Reservation;
use App\Models\Reunion\Salle;
use App\Models\User;
use Auth;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use InvalidArgumentException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    /**
     * @return View|RedirectResponse
     *
     * @throws BindingResolutionException
     * @throws RouteNotFoundException
     * @throws InvalidArgumentException
     */
    public function index(): View|RedirectResponse
    {
        /**
         * @var User
         */
        $user = Auth::user();
        if ($user && $user->isA('user')) {
            // Get current date/time
            $now = now();

            // Get upcoming reservations (start_time is after now)
            $upcomingReservations = Reservation::where('user_id', $user->id)
                ->where('start_time', '>=', $now)
                ->orderBy('start_time', 'asc')
                ->limit(5)
                ->get();

            // Get past reservations (end_time is before now)
            $pastReservations = Reservation::where('user_id', $user->id)
                ->where('end_time', '<', $now)
                ->orderBy('start_time', 'desc')
                ->limit(5)
                ->get();

            // Return the dashboard view with the reservations
            return view('index', [
                'upcomingReservations' => $upcomingReservations,
                'pastReservations' => $pastReservations,
                'user' => $user,

            ]);
        } if ($user && $user->isA('admin')) {
            // Get current date/time
            $now = now();

            // Get recent reservations
            $recentReservations = Reservation::with(['user', 'salle'])
                ->orderBy('start_time', 'desc')
                ->limit(10)
                ->get();

            // Calculate weekly reservations count
            $startOfWeek = $now->startOfWeek();
            $endOfWeek = $now->endOfWeek();
            $weeklyReservationsCount = Reservation::whereBetween('start_time', [$startOfWeek, $endOfWeek])
                ->count();

            // Calculate monthly reservations count
            $startOfMonth = $now->startOfMonth();
            $endOfMonth = $now->endOfMonth();
            $monthlyReservationsCount = Reservation::whereBetween('start_time', [$startOfMonth, $endOfMonth])
                ->count();

            // Calculate occupancy rate
            $salles = Salle::all();
            $totalReservationHours = 0;
            $totalPossibleHours = 0;

            foreach ($salles as $salle) {
                // Assuming 9-hour workday for the past 30 days
                $totalPossibleHours += 9 * 30;

                $reservations = Reservation::where('salle_id', $salle->id)
                    ->where('start_time', '>=', $now->copy()->subDays(30))
                    ->where('end_time', '<=', $now)
                    ->get();

                foreach ($reservations as $reservation) {
                    $startTime = \Carbon\Carbon::parse($reservation->start_time);
                    $endTime = \Carbon\Carbon::parse($reservation->end_time);
                    $totalReservationHours += $endTime->diffInHours($startTime);
                }
            }

            $occupancyRate = ($totalPossibleHours > 0) ? ($totalReservationHours / $totalPossibleHours) * 100 : 0;

            // Prepare weekly chart data
            $weeklyLabels = [];
            $weeklyData = [];
            for ($i = 0; $i < 7; $i++) {
                $date = $now->copy()->startOfWeek()->addDays($i);
                $weeklyLabels[] = $date->format('D');

                $dailyRate = $this->calculateDailyOccupancyRate($date);
                $weeklyData[] = round($dailyRate, 1);
            }

            // Prepare monthly chart data
            $monthlyLabels = [];
            $monthlyData = [];
            for ($i = 0; $i < 6; $i++) {
                $date = $now->copy()->subMonths($i);
                $monthlyLabels[] = $date->format('M');

                $monthlyRate = $this->calculateMonthlyOccupancyRate($date);
                $monthlyData[] = round($monthlyRate, 1);
            }

            // Reverse arrays for chronological order
            $monthlyLabels = array_reverse($monthlyLabels);
            $monthlyData = array_reverse($monthlyData);

            return view('dashboard_admin', [
                'recentReservations' => $recentReservations,
                'weeklyReservationsCount' => $weeklyReservationsCount,
                'monthlyReservationsCount' => $monthlyReservationsCount,
                'occupancyRate' => $occupancyRate,
                'weeklyLabels' => $weeklyLabels,
                'weeklyData' => $weeklyData,
                'monthlyLabels' => $monthlyLabels,
                'monthlyData' => $monthlyData,
                'user' => $user,
            ]);
        }
        return view('index', [
            'user' => $user,
        ]);
    }
    /**
     * Calculate daily occupancy rate for a given date
     *
     * @param \Carbon\Carbon $date
     * @return float
     */
    private function calculateDailyOccupancyRate(\Carbon\Carbon $date): float
    {
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        $reservations = Reservation::where('start_time', '>=', $startOfDay)
            ->where('end_time', '<=', $endOfDay)
            ->get();

        $totalReservationHours = 0;
        foreach ($reservations as $reservation) {
            $startTime = \Carbon\Carbon::parse($reservation->start_time);
            $endTime = \Carbon\Carbon::parse($reservation->end_time);
            $totalReservationHours += $endTime->diffInHours($startTime);
        }

        // Assuming 9-hour workday
        $totalPossibleHours = 9;

        return ($totalPossibleHours > 0) ? ($totalReservationHours / $totalPossibleHours) * 100 : 0;
    }
    /**
     * Calculate monthly occupancy rate for a given month
     *
     * @param \Carbon\Carbon $date
     * @return float
     */
    private function calculateMonthlyOccupancyRate(\Carbon\Carbon $date): float
    {
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $reservations = Reservation::where('start_time', '>=', $startOfMonth)
            ->where('end_time', '<=', $endOfMonth)
            ->get();

        $totalReservationHours = 0;
        foreach ($reservations as $reservation) {
            $startTime = \Carbon\Carbon::parse($reservation->start_time);
            $endTime = \Carbon\Carbon::parse($reservation->end_time);
            $totalReservationHours += $endTime->diffInHours($startTime);
        }

        // Assuming 9-hour workday for the month
        $totalPossibleHours = 9 * $date->daysInMonth;

        return ($totalPossibleHours > 0) ? ($totalReservationHours / $totalPossibleHours) * 100 : 0;
    }
}
