<x-layouts.app :title="'Services'">
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Services') }}
        </h1>
    </x-slot>

    <div class="p-6 bg-white dark:bg-zinc-900 shadow-sm sm:rounded-lg">
        @livewire('admin.service-list')
    </div>
</x-layouts.app>
