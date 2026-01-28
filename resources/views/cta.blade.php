<x-layouts.guest-app :title="'Pray'">
    <x-slot name="header">
        @if (Route::has('login'))
            <nav class="flex items-center justify-end gap-4">
                @auth
                    <a
                        href="{{ route('prayers.index') }}"
                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal"
                    >
                        Dashboard
                    </a>
                @else
                    <a
                        href="{{ route('login') }}"
                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
                    >
                        Log in
                    </a>
                @endauth
            </nav>
        @endif
    </x-slot>

    <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
        <div class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-es-lg rounded-ee-lg lg:rounded-ss-lg lg:rounded-ee-none flex flex-col items-center justify-center text-center">
            <div class="mb-4">
                <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
            </div>
            <h1 class="mb-4 font-medium text-lg">Share Your Prayer</h1>
            <ul class="flex flex-col gap-6 mb-8 max-w-md">
                <li class="space-y-2">
                    <p class="font-medium text-[#f53003] dark:text-[#FF4433]">Philippians 4:6</p>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">"Do not be anxious about anything, but in every situation, by prayer and petition, with thanksgiving, present your requests to God."</p>
                </li>
                <li class="space-y-2">
                    <p class="font-medium text-[#f53003] dark:text-[#FF4433]">1 Thessalonians 5:17</p>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">"Pray without ceasing."</p>
                </li>
                <li class="space-y-2">
                    <p class="font-medium text-[#f53003] dark:text-[#FF4433]">Matthew 6:6</p>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">"But when you pray, go into your room, close the door and pray to your Father, who is unseen."</p>
                </li>
            </ul>
            <ul class="flex gap-3 text-sm leading-normal mt-4">
                <li>
                    <a href="{{ route('prayers.create') }}" class="inline-block dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black px-5 py-1.5 bg-[#1b1b18] rounded-sm border border-black text-white text-sm leading-normal">
                        Create Prayer
                    </a>
                </li>
            </ul>

            {{-- <div class=""> --}}
                <livewire:testimonials-carousel />
            {{-- </div> --}}
        </div>
    </main>

    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif
</x-layouts.guest-app>
