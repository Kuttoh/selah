<div>
    @if ($hasExistingCallback)
        <div class="space-y-2">
            <div class="flex items-center justify-center gap-2 text-green-600 dark:text-green-400">
                <flux:icon name="check-circle" class="size-5" />
                <span class="font-medium">{{ __('Callback Requested') }}</span>
            </div>
            <p class="text-[#706f6c] dark:text-[#A1A09A] text-xs">
                {{ __('We have your callback request and will reach out soon.') }}
            </p>
        </div>
    @elseif ($submitted)
        <div class="space-y-2">
            <div class="flex items-center justify-center gap-2 text-green-600 dark:text-green-400">
                <flux:icon name="check-circle" class="size-5" />
                <span class="font-medium">{{ __('Callback Requested!') }}</span>
            </div>
            <p class="text-[#706f6c] dark:text-[#A1A09A] text-xs">
                {{ __('Thank you! We will contact you soon.') }}
            </p>
        </div>
    @else
        <div class="space-y-2">
            <label class="block text-[#706f6c] dark:text-[#A1A09A] mb-6">{{ __('Would you like us to contact you regarding this prayer?') }}</label>
            <x-guest-button type="button" wire:click="openModal">
                {{ __('Request Callback') }}
            </x-guest-button>
        </div>

        <flux:modal wire:model="showModal" class="md:w-96">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Request a Callback') }}</flux:heading>
                    <flux:text class="mt-2">{{ __('Share your contact details and we\'ll reach out to you.') }}</flux:text>
                </div>

                <form wire:submit="submit" class="space-y-4">
                    <flux:input
                        wire:model="name"
                        label="{{ __('Name') }}"
                        placeholder="{{ __('Your name') }}"
                    />
                    @error('name')
                        <p class="text-red-600 text-sm -mt-2">{{ $message }}</p>
                    @enderror

                    <flux:input
                        wire:model="phone"
                        type="tel"
                        label="{{ __('Phone Number') }}"
                        placeholder="{{ __('0712345678 or +254712345678') }}"
                    />
                    @error('phone')
                        <p class="text-red-600 text-sm -mt-2">{{ $message }}</p>
                    @enderror

                    <flux:select wire:model="serviceId" label="{{ __('Service (Optional)') }}" placeholder="{{ __('Select a service...') }}">
                        @foreach($services as $service)
                            <flux:select.option value="{{ $service->id }}">{{ $service->name }}</flux:select.option>
                        @endforeach
                    </flux:select>

                    <div class="flex pt-4 justify-between">
                        <flux:button wire:click="closeModal" variant="ghost">{{ __('Cancel') }}</flux:button>
                        <flux:button type="submit" variant="primary">{{ __('Submit') }}</flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
