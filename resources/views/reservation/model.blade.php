<x-app-layout>
  @php
            $isset = isset($reservation)&&$reservation->exists ? true : false;
          @endphp
  <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen">
    <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <h2 class="text-2xl font-semibold mb-6">
            {{ $isset ? 'Modifier la réservation' : 'Faire une réservation' }}
          </h2>

          <form method="POST" action="{{ $isset ? route('reservation.update', $reservation->id) : route('reservation.store') }}">
            @csrf
            @if($isset)
              @method('PUT')
            @endif

            <div class="mb-4">
              <x-input-label for="reservation_date" :value="'Date de réservation'" />
              <x-text-input type="date" name="reservation_date" id="reservation_date"
                :value="old('reservation_date', $isset ? date('Y-m-d', strtotime($reservation->start_time)) : '')"
                required class="w-full md:w-1/2" />
              @error('reservation_date')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
              @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div>
                <x-input-label for="start_time" :value="'Heure de début'" />
                <x-text-input type="time" name="start_time" id="start_time"
                  :value="old('start_time', $isset ? date('H:i', strtotime($reservation->start_time)) : '')"
                  required class="w-full" />
                @error('start_time')
                  <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <x-input-label for="end_time" :value="'Heure de fin'" />
                <x-text-input type="time" name="end_time" id="end_time"
                  :value="old('end_time', $isset ? date('H:i', strtotime($reservation->end_time)) : '')"
                  required class="w-full" />
                @error('end_time')
                  <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
              </div>
            </div>

            <div class="mb-6 w-full">
              <x-input-label for="salle_id" :value="'Salle'" />
              <select name="salle_id" id="salle_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full md:w-1/2">
                <option value="">Sélectionnez une salle</option>
                @foreach($salles as $salle)
                  <option value="{{ $salle->id }}"

                      {{ old('salle_id', $isset ? $reservation->salle_id : '') == $salle->id ? 'selected' : '' }}
                      {{ (isset($from_salle)&&$from_salle->id == $salle->id) ? 'selected' : '' }}
                      data-salle-id="{{ $salle->id }}"
                      class="salle-option">
                  {{ $salle->nom }} (Capacité: {{ $salle->capacite }})
                  <span class="availability-status">- Vérifiez les horaires</span>
                  </option>
                @endforeach
              </select>
              @error('salle_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
              @enderror
            </div>

            <div class="flex justify-end">
              <a href="{{ route('reservation.index') }}"
                 class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 font-semibold py-2 px-4 rounded-md mr-2">
                Annuler
              </a>
              <button type="submit" id="submit-button"
                  class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-md">
                {{ $isset ? 'Mettre à jour' : 'Réserver' }}
              </button>
            </div>
          </form>

          <script>
            document.addEventListener('DOMContentLoaded', function() {
              const dateInput = document.getElementById('reservation_date');
              const startTimeInput = document.getElementById('start_time');
              const endTimeInput = document.getElementById('end_time');
              const salleSelect = document.getElementById('salle_id');
              const submitButton = document.getElementById('submit-button');

              // Validate times
              function validateTimes() {
                if (!startTimeInput.value || !endTimeInput.value || !dateInput.value) return true;

                const startDateTime = new Date(`${dateInput.value}T${startTimeInput.value}`);
                const endDateTime = new Date(`${dateInput.value}T${endTimeInput.value}`);

                if (startDateTime >= endDateTime) {
                  alert("L'heure de début doit être antérieure à l'heure de fin");
                  return false;
                }
                return true;
              }

              // Check times when changed
              [startTimeInput, endTimeInput].forEach(input => {
                input.addEventListener('change', function() {
                  validateTimes();
                  checkAvailability();
                });
              });

              // Check availability when date changes
              dateInput.addEventListener('change', checkAvailability);

              // Validate form before submission
              document.querySelector('form').addEventListener('submit', function(e) {
                if (!validateTimes()) {
                  e.preventDefault();
                }
              });

              function checkAvailability() {
                const date = dateInput.value;
                const startTime = startTimeInput.value;
                const endTime = endTimeInput.value;

                if(!date || !startTime || !endTime) return;

                const startDateTime = `${date}T${startTime}`;
                const endDateTime = `${date}T${endTime}`;

                // Fetch availability from server
                fetch(`/api/check-availability?start_time=${startDateTime}&end_time=${endDateTime}`)
                  .then(response => response.json())
                  .then(data => {
                    // Update options to show availability
                    Array.from(salleSelect.options).forEach(option => {
                      if(!option.value) return; // Skip the placeholder option

                      const salleId = option.getAttribute('data-salle-id');
                      const isAvailable = data.available_salles.includes(parseInt(salleId));

                      if(isAvailable) {
                        option.classList.add('available');
                        option.classList.remove('unavailable');
                        option.innerHTML = `${option.textContent.split(' - ')[0]} - <span class="text-green-500">Disponible</span>`;
                      } else {
                        option.classList.add('unavailable');
                        option.classList.remove('available');
                        option.innerHTML = `${option.textContent.split(' - ')[0]} - <span class="text-red-500">Non disponible</span>`;
                      }
                    });
                  });
              }
            });
          </script>

          <style>
            .unavailable {
              color: #999;
              background-color: #f5f5f5;
            }
          </style>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
