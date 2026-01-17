<x-layouts.app :title="'Prayer Requests'">
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Prayer Requests') }}
        </h1>
    </x-slot>

    <div class="p-6 bg-white dark:bg-zinc-900 shadow-sm sm:rounded-lg">
        @livewire('admin.prayer-list')
    </div>
</x-layouts.app>