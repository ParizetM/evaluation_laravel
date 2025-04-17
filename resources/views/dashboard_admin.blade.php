<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Tableau de bord administrateur') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Taux d'occupation global</h3>
            <p class="text-3xl font-bold mt-2 dark:text-white">{{ number_format($occupancyRate, 1) }}%</p>
          </div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Réservations cette semaine</h3>
            <p class="text-3xl font-bold mt-2 dark:text-white">{{ $weeklyReservationsCount }}</p>
          </div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Réservations ce mois</h3>
            <p class="text-3xl font-bold mt-2 dark:text-white">{{ $monthlyReservationsCount }}</p>
          </div>
        </div>
      </div>

      <!-- Charts Section -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Weekly Chart -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Taux de réservation par semaine</h3>
            <canvas id="weeklyChart" height="200"></canvas>
          </div>
        </div>
        <!-- Monthly Chart -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Taux de réservation par mois</h3>
            <canvas id="monthlyChart" height="200"></canvas>
          </div>
        </div>
      </div>

      <!-- Recent Reservations -->
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
          <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Réservations récentes</h3>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Utilisateur</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Salle</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Début</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fin</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($recentReservations as $reservation)
                  <tr>
                    <td class="px-6 py-4 whitespace-nowrap dark:text-gray-200">{{ $reservation->user->first_name }}
                      {{ $reservation->user->last_name }}</td>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap dark:text-gray-200">{{ $reservation->salle->nom }}</td>
                    <td class="px-6 py-4 whitespace-nowrap dark:text-gray-200">{{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap dark:text-gray-200">{{ \Carbon\Carbon::parse($reservation->end_time)->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                      <a href="{{ route('reservation.show', $reservation) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-2">Voir</a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-center dark:text-gray-200" colspan="5">Aucune réservation récente</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const isDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
      const textColor = isDarkMode ? '#e5e7eb' : '#374151';
      const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

      // Weekly Chart
      const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
      const weeklyChart = new Chart(weeklyCtx, {
        type: 'bar',
        data: {
          labels: {!! json_encode($weeklyLabels) !!},
          datasets: [{
            label: 'Taux de réservation (%)',
            data: {!! json_encode($weeklyData) !!},
            backgroundColor: isDarkMode ? 'rgba(96, 165, 250, 0.5)' : 'rgba(59, 130, 246, 0.5)',
            borderColor: isDarkMode ? 'rgb(96, 165, 250)' : 'rgb(59, 130, 246)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
              max: 100,
              ticks: {
                callback: function(value) {
                  return value + '%';
                },
                color: textColor
              },
              grid: {
                color: gridColor
              }
            },
            x: {
              ticks: {
                color: textColor
              },
              grid: {
                color: gridColor
              }
            }
          },
          plugins: {
            legend: {
              labels: {
                color: textColor
              }
            }
          }
        }
      });

      // Monthly Chart
      const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
      const monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
          labels: {!! json_encode($monthlyLabels) !!},
          datasets: [{
            label: 'Taux de réservation (%)',
            data: {!! json_encode($monthlyData) !!},
            backgroundColor: isDarkMode ? 'rgba(52, 211, 153, 0.2)' : 'rgba(16, 185, 129, 0.2)',
            borderColor: isDarkMode ? 'rgb(52, 211, 153)' : 'rgb(16, 185, 129)',
            borderWidth: 2,
            tension: 0.1
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
              max: 100,
              ticks: {
                callback: function(value) {
                  return value + '%';
                },
                color: textColor
              },
              grid: {
                color: gridColor
              }
            },
            x: {
              ticks: {
                color: textColor
              },
              grid: {
                color: gridColor
              }
            }
          },
          plugins: {
            legend: {
              labels: {
                color: textColor
              }
            }
          }
        }
      });
    });
  </script>
  @endpush
</x-app-layout>
