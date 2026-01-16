<x-layouts.guest-app :title="'Submit Prayer'">
    <x-slot name="header">
        <nav class="flex items-center justify-end gap-4">
            <a href="{{ route('home') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
                Home
            </a>
        </nav>
    </x-slot>

    <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
        <div class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-es-lg rounded-ee-lg lg:rounded-ss-lg lg:rounded-ee-none flex flex-col items-center justify-center text-center">
            @livewire('create-prayer')
        </div>
    </main>
</x-layouts.guest-app>
