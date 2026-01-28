<x-layouts.guest-app :title="__('Prayer Progress')">

    <main class="flex max-w-[335px] w-full flex-col lg:max-w-3xl lg:flex-row">
        <div class="text-[13px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] text-[#1b1b18] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-es-lg rounded-ee-lg lg:rounded-ss-lg lg:rounded-ee-none flex flex-col gap-6">
            <div class="space-y-2 justify-items-center text-center">
                <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
                <h1 class="font-medium text-lg">{{ $statusMessage }}</h1>
            </div>

            <div class="space-y-6 text-center">
                <div class="space-y-2">
                    <label class="block font-medium text-sm text-[#f53003] dark:text-[#FF4433]">{{ __('Your Prayer') }}</label>
                    <p class="text-[#706f6c] dark:text-[#A1A09A] leading-relaxed text-xs"><em>{{ $prayerText }}</em></p>
                </div>
                <div class="pt-6 border-t border-[#19140035] dark:border-[#3E3E3A]">
                    <div class="space-y-2">
                        <label class="block text-[#706f6c] dark:text-[#A1A09A] mb-6">{{ __('Would you like us to contact you regarding this prayer?') }}</label>
                        <a
                            href="#"
                            class="inline-block dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black px-5 py-1.5 bg-[#1b1b18] rounded-sm border border-black text-white leading-normal"
                        >
                            {{ __('Request Callback') }}
                        </a>
                    </div>
                </div>
                <div class="pt-6 border-t border-[#19140035] dark:border-[#3E3E3A]">
                    <livewire:mark-prayer-answered :public-token="$publicToken" />
                </div>
            </div>

            {{-- <!-- TODO: Update the link below to point to the callback request page -->
            <div class="pt-6 border-t border-[#19140035] dark:border-[#3E3E3A] flex gap-3 text-sm justify-between">
                <a
                    href="{{ route('home') }}"
                    class="inline-block dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black px-5 py-1.5 bg-[#1b1b18] rounded-sm border border-black text-white leading-normal"
                >
                    {{ __('Request Callback') }}
                </a>
                                <a
                    href="{{ route('home') }}"
                    class="inline-block dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black px-5 py-1.5 bg-[#1b1b18] rounded-sm border border-black text-white leading-normal"
                >
                    {{ __('Update Status') }}
                </a>
            </div> --}}
        </div>
    </main>

    <div class="h-14.5 hidden lg:block"></div>
</x-layouts.guest-app>
