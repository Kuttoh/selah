<x-layouts.app :title="'Callbacks'">
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Callback Requests') }}
        </h1>
    </x-slot>

    <div class="p-6 bg-white dark:bg-zinc-900 shadow-sm sm:rounded-lg">
        @livewire('admin.callback-list')
    </div>
</x-layouts.app>
