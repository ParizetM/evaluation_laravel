<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Mes Réservations') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-800">
          <div class="mb-6 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Vos réservations actuelles</h3>
            <a href="{{ route('reservation.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              Nouvelle réservation
            </a>
          </div>

          @if(count($reservations) > 0)
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Salle</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Capacité</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Surface</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date et Horaire</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Statut</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                  @foreach($reservations as $reservation)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $reservation->salle->nom }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $reservation->salle->capacite }} personnes</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $reservation->salle->surface }} m²</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Le
                          {{ \Carbon\Carbon::parse($reservation->start_time)->format(format: 'd/m/Y') }} de
                          {{ \Carbon\Carbon::parse($reservation->start_time)->format(format: 'H:i') }} à
                          {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        @php
                          $now = \Carbon\Carbon::now();
                          $startTime = \Carbon\Carbon::parse($reservation->start_time);
                          $endTime = \Carbon\Carbon::parse($reservation->end_time);
                        @endphp

                        @if($now->isAfter($endTime))
                          <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                            Terminée
                          </span>
                        @elseif($now->isBetween($startTime, $endTime))
                          <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            En cours
                          </span>
                        @else
                          <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            À venir
                          </span>
                        @endif
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">

                          @if(\Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($reservation->start_time)))
                            <form action="{{ route('reservation.destroy', $reservation->id) }}" method="POST" class="inline">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="text-red-600 dark:text-red-400 hover:underline" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                Annuler
                              </button>
                            </form>
                          @endif
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <div class="mt-6">
              {{ $reservations->links() }}
            </div>
          @else
            <div class="text-center py-10">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Vous n'avez aucune réservation</h3>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Réservez une salle pour vos réunions ou événements.</p>
              <div class="mt-6">
                <a href="{{ route('reservation.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                  <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                  Nouvelle réservation
                </a>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
