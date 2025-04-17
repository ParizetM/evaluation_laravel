@if ($matiere->typeAffichageStock() == 2)
    <div class="relative inline-block group">
        <span class="cursor-pointer underline">
            {{ $matiere->quantite() }} {{ $matiere->unite->short }}
        </span>

        <div
            class="absolute invisible opacity-0 group-hover:visible group-active:visible group-hover:opacity-100 bottom-full left-1/2 -translate-x-1/2 mb-3 w-fit transition-all duration-300 ease-out transform group-hover:translate-y-0 translate-y-2 z-100">
            <div
                class="relative p-2 rounded-lg border shadow-md
            bg-white border-gray-200 text-gray-800
            dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                @if ($matiere->quantite() > 1)
                    <h3 class="text-sm font-semibold mb-2 whitespace-nowrap border-b">{{ $matiere->quantite() }}
                        {{ $matiere->unite->full_plural }}</h3>
                @else
                    <h3 class="text-sm font-semibold mb-2 whitespace-nowrap border-b">{{ $matiere->quantite() }}
                        {{ $matiere->unite->full }}</h3>
                @endif
                <ul class="text-sm space-y-1">
                    @foreach ($matiere->stock as $stock)
                        <li>- {{ $stock->quantite }} x {{ $stock->valeur_unitaire }} {{ $matiere->unite->short }}</li>
                    @endforeach
                </ul>

            </div>
        </div>
    </div>
@else
    @if ($matiere->quantite() > 1)
        <span class="" title="{{ $matiere->quantite() }} {{ $matiere->unite->full_plural }}">
        @else
            <span class="" title="{{ $matiere->quantite() }} {{ $matiere->unite->full }}">
    @endif
    {{ $matiere->quantite() }} {{ $matiere->unite->short }}
    </span>
@endif
