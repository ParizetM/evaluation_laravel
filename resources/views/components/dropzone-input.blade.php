@props(['id', 'name'])

<div class="flex items-center justify-center w-full">
    <label for="{{ $id ?? 'dropzone-file' }}" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-700 dark:bg-gray-800 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600 {{ $class ?? '' }}">
        <div class="flex flex-col items-center justify-center pt-5 pb-6">
            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
            </svg>
            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400 text-center"><span class="font-semibold">Cliquez pour télécharger</span> ou faites glisser et déposez</p>
            <p class="text-xs text-gray-500 dark:text-gray-400" id="file-name">PDF</p>
        </div>
        <input id="{{ $id ?? 'dropzone-file' }}" name="{{ $name ?? 'dropzone-file' }}" type="file" class="hidden" {{  $attributes }} onchange="displayFileName(event)"/>
    </label>
</div>
<p id="file-name" class="mt-2 text-sm text-gray-500 dark:text-gray-400"></p>

<script>
    function displayFileName(event) {
        const input = event.target;
        const fileName = input.files[0] ? input.files[0].name : 'PDF';
        document.getElementById('file-name').innerHTML = '<strong>'+fileName+'</strong>';
    }
</script>
