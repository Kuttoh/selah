<x-layouts.guest-app :title="'Pray'">
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
            <ul class="flex flex-col gap-6 mb-8 max-w-md">
                <li class="space-y-2">
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">You are welcome to submit a prayer or request us to call you.</p>
                </li>
            </ul>
            <ul class="flex gap-3 text-sm leading-normal">
                <li>
                    <a href="{{ route('callbacks.request') }}" class="inline-block dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black px-5 py-1.5 bg-[#1b1b18] rounded-sm border border-black text-white text-sm leading-normal">
                        Request Callback
                    </a>
                </li>
                <li>
                    <a href="{{ route('prayers.create') }}" class="inline-block dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black px-5 py-1.5 bg-[#1b1b18] rounded-sm border border-black text-white text-sm leading-normal">
                        Submit Prayer
                    </a>
                </li>
            </ul>

            <livewire:testimonials.testimonials-carousel />

            <div class="mt-4">
                <livewire:testimonials.submit-testimonial />
            </div>
        </div>
    </main>

</x-layouts.guest-app>
