<div>
    @if ($submitted)
        <div class="flex items-center justify-center gap-2 text-green-600 dark:text-green-400">
            <flux:icon name="check-circle" class="size-5" />
            <span class="font-medium">{{ __('Thank you for sharing your testimony!') }}</span>
        </div>
    @else
        <div class="flex gap-3 text-sm leading-normal">
            <button
                wire:click="openModal"
                type="button"
                class="inline-block dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black px-5 py-1.5 bg-[#1b1b18] rounded-sm border border-black text-white text-sm leading-normal"
            >
                {{ __('Share your testimony') }}
            </button>
        </div>

        <flux:modal wire:model="showModal" class="md:w-96">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Share Your Testimony') }}</flux:heading>
                    <flux:text class="mt-2">{{ __('Tell us how God has worked in your life.') }}</flux:text>
                </div>

                <form wire:submit="submit" class="space-y-4">
                    <flux:textarea
                        wire:model="content"
                        label="{{ __('Your Testimony') }}"
                        placeholder="{{ __('Share your testimony here...') }}"
                        rows="4"
                    />

                    <flux:input
                        wire:model="displayName"
                        label="{{ __('Display Name (Optional)') }}"
                        placeholder="{{ __('Leave blank to remain anonymous') }}"
                    />

                    <p class="text-[#706f6c] dark:text-[#A1A09A] text-xs italic">
                        {{ __('Your testimony will be reviewed by our admin team before appearing on our site.') }}
                    </p>

                    <div class="flex pt-4 justify-between">
                        <flux:button wire:click="closeModal" variant="ghost">{{ __('Cancel') }}</flux:button>
                        <flux:button type="submit" variant="primary">{{ __('Submit') }}</flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
