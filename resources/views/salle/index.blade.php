<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">Salles de Réunion</h1>
                @can('salle-create')
                    <a href="{{ route('salle.create') }}" class="btn dark:bg-gray-800">Créer une salle</a>
                @endcan
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($salles as $salle)
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition-all duration-200 hover:shadow-md">
                        <div class="p-6 ">
                            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-2">{{ $salle->nom }}</h2>
                            <div class="flex flex-col space-y-2 text-gray-600 dark:text-gray-300">
                                <p class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    Capacité: {{ $salle->capacite }} personnes
                                </p>
                                <p class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z">
                                        </path>
                                    </svg>
                                    Surface: {{ $salle->surface }} m²
                                </p>
                            </div>
                        </div>
                        <div class="p-4 justify-end flex gap-2">
                          @can('salle-delete')
                                <form action="{{ route('salle.destroy', $salle) }}" method="POST" class="ms-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn bg-red-600 hover:bg-red-700">Supprimer</button>
                                </form>
                            @endcan
                            @can('salle-update')
                                <a href="{{ route('salle.edit', $salle) }}" class="btn">Modifier</a>
                            @endcan

                            <a href="{{ route('reservation.create', ['salle' => $salle->id]) }}" class="btn">Réserver</a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-600 dark:text-gray-300">
                            Aucune salle disponible pour le moment.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</x-app-layout>
