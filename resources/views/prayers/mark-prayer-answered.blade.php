<div>
    @if ($prayer->status === \App\Enums\PrayerStatus::Answered)
        <div class="space-y-2">
            <div class="flex items-center justify-center gap-2 text-green-600 dark:text-green-400">
                <flux:icon name="check-circle" class="size-5" />
                <span class="font-medium">{{ __('Prayer Answered!') }}</span>
            </div>
            @if ($prayer->answered_at)
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-xs">
                    {{ __('Marked as answered on :date', ['date' => $prayer->answered_at->format('F j, Y')]) }}
                </p>
            @endif
        </div>
    @elseif ($submitted)
        <div class="space-y-2">
            <div class="flex items-center justify-center gap-2 text-green-600 dark:text-green-400">
                <flux:icon name="check-circle" class="size-5" />
                <span class="font-medium">{{ __('Thank you!') }}</span>
            </div>
            <p class="text-[#706f6c] dark:text-[#A1A09A] text-xs">
                {{ __('Your prayer has been marked as answered.') }}
            </p>
        </div>
    @else
        <div class="space-y-2">
            <label class="block text-[#706f6c] dark:text-[#A1A09A] mb-6">{{ __('Has your prayer been answered? Please let us know!') }}</label>
            <button
                wire:click="openModal"
                type="button"
                class="inline-block dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black px-5 py-1.5 bg-[#1b1b18] rounded-sm border border-black text-white leading-normal"
            >
                {{ __('Mark as Answered') }}
            </button>
        </div>

        <flux:modal wire:model="showModal" class="md:w-96">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Mark Prayer as Answered') }}</flux:heading>
                    <flux:text class="mt-2">{{ __('We rejoice with you! Would you like to share your testimony?') }}</flux:text>
                </div>

                <form wire:submit="markAsAnswered" class="space-y-4">
                    <flux:textarea
                        wire:model="testimonialContent"
                        label="{{ __('Your Testimony (Optional)') }}"
                        placeholder="{{ __('Share how God answered your prayer...') }}"
                        rows="4"
                    />

                    <flux:input
                        wire:model="displayName"
                        label="{{ __('Display Name (Optional)') }}"
                        placeholder="{{ __('How should we display your name?') }}"
                    />

                    <flux:field variant="inline">
                        <flux:checkbox wire:model="isPublic" />
                        <flux:label class="text-[#706f6c] dark:text-[#A1A09A] text-xs italic">
                            {{ __('Allow your testimony to be displayed to encourage others. Requires admin approval before appearing.') }}
                        </flux:label>
                    </flux:field>

                    <div class="flex pt-4 justify-between">
                        <flux:button wire:click="closeModal" variant="ghost">{{ __('Cancel') }}</flux:button>
                        <flux:button type="submit" variant="primary">{{ __('Submit') }}</flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
