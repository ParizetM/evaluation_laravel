@props(['value','optionnel' => false])

@if ($optionnel)
    <label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700 dark:text-gray-300']) }}>
        {{ $value ?? $slot }}
        <small>(Optionnel)</small>
    </label>

@else
<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700 dark:text-gray-300']) }}>
    {{ $value ?? $slot }}
</label>
@endif

