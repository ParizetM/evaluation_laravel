@if (Auth::user()->isA('admin'))
    @include('dashboard_admin')
@else
    <x-app-layout>
        <div class="container mx-auto mt-8 p-4 bg-white dark:bg-gray-800 rounded-2xl">
            @include('dashboard_user')
        </div>
    </x-app-layout>
@endif
