
@if ($testimonials->isNotEmpty())
    <div
        x-data="{
            current: 0,
            total: {{ $testimonials->count() }},
            autoplay: null,
            init() {
                this.startAutoplay();
            },
            startAutoplay() {
                this.autoplay = setInterval(() => this.next(), 6000);
            },
            stopAutoplay() {
                clearInterval(this.autoplay);
            },
            next() {
                this.current = (this.current + 1) % this.total;
            },
            prev() {
                this.current = (this.current - 1 + this.total) % this.total;
            },
            goTo(index) {
                this.current = index;
            }
        }"
        @mouseenter="stopAutoplay()"
        @mouseleave="startAutoplay()"
        class="relative max-w-2xl mx-auto mt-6 pt-2 border-t border-[#19140035] dark:border-[#3E3E3A] w-full">
        <div class="space-y-2 text-center">
            <div class="relative flex items-center justify-center mt-4">
                @foreach ($testimonials as $index => $testimonial)
                    <div
                        x-show="current === {{ $index }}"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-x-4"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-x-0"
                        x-transition:leave-end="opacity-0 transform -translate-x-4"
                        class="flex flex-col items-center justify-center px-8"
                        :class="current === {{ $index }} ? '' : 'absolute inset-0'"
                    >
                        <blockquote class="text-[#706f6c] dark:text-[#A1A09A] italic leading-relaxed text-sm">
                            "{{ Str::limit($testimonial->content, 150) }}"
                        </blockquote>
                        <cite class="mt-2 text-[#f53003] dark:text-[#FF4433] not-italic font-medium text-sm">
                            — {{ $testimonial->display_name ?? __('Anonymous') }}
                        </cite>
                    </div>
                @endforeach
            </div>

            @if ($testimonials->count() > 1)
                <div class="flex items-center justify-center gap-4">
                    <button
                        @click="prev()"
                        type="button"
                        class="p-2 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors"
                        aria-label="{{ __('Previous testimonial') }}"
                    >
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <div class="flex gap-2">
                        @foreach ($testimonials as $index => $testimonial)
                            <button
                                @click="goTo({{ $index }})"
                                type="button"
                                :class="current === {{ $index }} ? 'bg-[#f53003] dark:bg-[#FF4433]' : 'bg-[#706f6c]/30 dark:bg-[#A1A09A]/30'"
                                class="size-2 rounded-full transition-colors"
                                aria-label="{{ __('Go to testimonial :number', ['number' => $index + 1]) }}"
                            ></button>
                        @endforeach
                    </div>

                    <button
                        @click="next()"
                        type="button"
                        class="p-2 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors"
                        aria-label="{{ __('Next testimonial') }}"
                    >
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            @endif
        </div>
    </div>
@endif
