  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Welcome Section -->
          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
              <div class="p-6 bg-white dark:bg-gray-800">
                  <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Bienvenue, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                  </h3>
                  <div class="flex space-x-4">
                      <a href="{{ route('reservation.create') }}"
                          class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                          <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                              xmlns="http://www.w3.org/2000/svg">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                          </svg>
                          Nouvelle réservation
                      </a>
                      <a href="{{ route('reservation.mes_reservations') }}"
                          class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                          <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                              xmlns="http://www.w3.org/2000/svg">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                          </svg>
                          Toutes mes réservations
                      </a>
                  </div>
              </div>
          </div>

          <!-- Upcoming Reservations -->
          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
              <div class="p-6 bg-white dark:bg-gray-800">
                  <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Réservations à venir</h3>

                  @if (count($upcomingReservations) > 0)
                      <div class="overflow-x-auto">
                          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                              <thead class="bg-gray-50 dark:bg-gray-700">
                                  <tr>
                                      <th scope="col"
                                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                          Salle</th>
                                      <th scope="col"
                                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                          Date</th>
                                      <th scope="col"
                                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                          Horaire</th>
                                      <th scope="col"
                                          class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                          Actions</th>
                                  </tr>
                              </thead>
                              <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                  @foreach ($upcomingReservations as $reservation)
                                      <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                          <td class="px-6 py-4 whitespace-nowrap">
                                              <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                  {{ $reservation->salle->nom }}</div>
                                              <div class="text-xs text-gray-500 dark:text-gray-400">Capacité:
                                                  {{ $reservation->salle->capacite }} personnes</div>
                                          </td>
                                          <td class="px-6 py-4 whitespace-nowrap">
                                              <div class="text-sm text-gray-500 dark:text-gray-400">
                                                  {{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y') }}
                                              </div>
                                          </td>
                                          <td class="px-6 py-4 whitespace-nowrap">
                                              <div class="text-sm text-gray-500 dark:text-gray-400">
                                                  {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} -
                                                  {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
                                              </div>
                                          </td>
                                          <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                              <div class="flex justify-end space-x-2">
                                                  <form action="{{ route('reservation.destroy', $reservation->id) }}"
                                                      method="POST" class="inline">
                                                      @csrf
                                                      @method('DELETE')
                                                      <button type="submit"
                                                          class="text-red-600 dark:text-red-400 hover:underline"
                                                          onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                                          Annuler
                                                      </button>
                                                  </form>
                                              </div>
                                          </td>
                                      </tr>
                                  @endforeach
                              </tbody>
                          </table>
                      </div>

                      @if (count($upcomingReservations) > 5)
                          <div class="mt-4 text-center">
                              <a href="{{ route('reservation.index') }}"
                                  class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                  Voir toutes vos réservations à venir
                              </a>
                          </div>
                      @endif
                  @else
                      <div class="text-center py-6">
                          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                              viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                              </path>
                          </svg>
                          <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Aucune réservation à
                              venir</h3>
                          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Réservez une salle pour vos
                              prochaines réunions.</p>
                          <div class="mt-6">
                              <a href="{{ route('reservation.create') }}"
                                  class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                  <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                      viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                  </svg>
                                  Nouvelle réservation
                              </a>
                          </div>
                      </div>
                  @endif
              </div>
          </div>

          <!-- Past Reservations -->
          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 bg-white dark:bg-gray-800">
                  <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Réservations passées</h3>

                  @if (count($pastReservations) > 0)
                      <div class="overflow-x-auto">
                          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                              <thead class="bg-gray-50 dark:bg-gray-700">
                                  <tr>
                                      <th scope="col"
                                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                          Salle</th>
                                      <th scope="col"
                                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                          Date</th>
                                      <th scope="col"
                                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                          Horaire</th>

                                  </tr>
                              </thead>
                              <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                  @foreach ($pastReservations as $reservation)
                                      <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                          <td class="px-6 py-4 whitespace-nowrap">
                                              <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                  {{ $reservation->salle->nom }}</div>
                                              <div class="text-xs text-gray-500 dark:text-gray-400">Capacité:
                                                  {{ $reservation->salle->capacite }} personnes</div>
                                          </td>
                                          <td class="px-6 py-4 whitespace-nowrap">
                                              <div class="text-sm text-gray-500 dark:text-gray-400">
                                                  {{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y') }}
                                              </div>
                                          </td>
                                          <td class="px-6 py-4 whitespace-nowrap">
                                              <div class="text-sm text-gray-500 dark:text-gray-400">
                                                  {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}
                                                  -
                                                  {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
                                              </div>
                                          </td>
                                      </tr>
                                  @endforeach
                              </tbody>
                          </table>
                      </div>

                      @if (count($pastReservations) > 5)
                          <div class="mt-4 text-center">
                              <a href="{{ route('reservation.index') }}?past=1"
                                  class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                  Voir toutes vos réservations passées
                              </a>
                          </div>
                      @endif
                  @else
                      <div class="text-center py-6">
                          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                              viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                          </svg>
                          <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Aucune réservation
                              passée</h3>
                          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Votre historique de réservations
                              apparaîtra ici.</p>
                      </div>
                  @endif
              </div>
          </div>
      </div>
  </div>
