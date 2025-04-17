<x-app-layout  >
<div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen">
  <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-6 text-gray-900 dark:text-gray-100">
        <h2 class="text-2xl font-semibold mb-6">
          {{ isset($salle) ? 'Modifier la salle' : 'Ajouter une salle' }}
        </h2>

        <form method="POST" action="{{ isset($salle) ? route('salle.update', $salle->id) : route('salle.store') }}">
          @csrf
          @if(isset($salle))
            @method('PUT')
          @endif

            <div class="mb-4 w-full">
            <x-input-label for="nom" :value="'Nom'" />
            <x-text-input type="text" name="nom" id="nom" :value="old('nom', isset($salle) ? $salle->nom : '')" required class="w-full md:w-1/2" />
            @error('nom')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            </div>

            <div class="mb-4">
            <x-input-label for="capacite" :value="'Capacité'" />
            <x-text-input type="number" name="capacite" id="capacite" :value="old('capacite', isset($salle) ? $salle->capacite : '')" required class="w-full md:w-1/2" />
            @error('capacite')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            </div>

            <div class="mb-6">
            <x-input-label for="surface" :value="'Surface (m²)'" />
            <x-text-input type="number" name="surface" id="surface" step="0.01" :value="old('surface', isset($salle) ? $salle->surface : '')" required class="w-full md:w-1/2" />
            @error('surface')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            </div>

          <div class="flex justify-end">
            <a href="{{ route('salle.index') }}"
               class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 font-semibold py-2 px-4 rounded-md mr-2">
              Annuler
            </a>
            <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-md">
              {{ isset($salle) ? 'Mettre à jour' : 'Créer' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</x-app-layout>
