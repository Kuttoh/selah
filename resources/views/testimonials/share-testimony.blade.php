<div>
    @if($submitted)
        <div class="flex flex-col items-center">
            <div class="">
                <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
            </div>
            <h1 class="font-medium text-lg text-green-700 mb-2">Thank You for Sharing 🙌🏾</h1>
            <p class="mb-4 text-center text-[#706f6c] dark:text-[#A1A09A]">
                Your testimony has been received and will be reviewed by our team before appearing on our site.
            </p>
            <x-guest-button href="{{ route('home') }}">
                Return Home
            </x-guest-button>
        </div>
    @else
        <div class="flex flex-col items-center text-center mb-4">
            <div class="p-2">
                <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
            </div>
            <h1 class="mb-6 font-medium text-lg">Share Your Testimony</h1>
            <p class="text-[#706f6c] dark:text-[#A1A09A]">Tell us how God has worked in your life 🙏</p>
        </div>
        <div class="flex flex-col items-center text-center">
            <form wire:submit="submit" class="w-full max-w-md space-y-4">
                <div class="mb-4">
                    <label for="content" class="block text-sm font-medium mb-2">Your Testimony</label>
                    <div class="relative">
                        <textarea
                            wire:model.blur="content"
                            id="content"
                            rows="6"
                            class="w-full border box-border px-3 py-2 border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-600 focus:border-transparent"
                            placeholder="Share your testimony here..."
                        ></textarea>
                        <div wire:dirty.class.remove="hidden" wire:target="content" class="hidden absolute right-3 top-3 text-green-600">
                            <flux:icon name="check" class="size-4" />
                        </div>
                    </div>
                    @error('content')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="displayName" class="block text-sm font-medium mb-2">Display Name (Optional)</label>
                    <div class="relative">
                        <input
                            wire:model.blur="displayName"
                            type="text"
                            id="displayName"
                            class="w-full px-3 py-2 border border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-600 focus:border-transparent"
                            placeholder="Leave blank to remain anonymous"
                        />
                        <div wire:dirty.class.remove="hidden" wire:target="displayName" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-green-600">
                            <flux:icon name="check" class="size-4" />
                        </div>
                    </div>
                    @error('displayName')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4 flex items-start gap-2 text-left">
                    <flux:checkbox wire:model="isPublic" id="isPublic" checked />
                    <label for="isPublic" class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        Allow my testimony to be displayed publicly on the site
                    </label>
                </div>

                <p class="text-[#706f6c] dark:text-[#A1A09A] text-xs italic mb-4">
                    Your testimony will be reviewed by our team before appearing on our site.
                </p>

                <x-guest-button type="submit" :fullWidth="true" wire:loading.attr="disabled" :disabled="false">
                    <span wire:loading.remove>Share Testimony</span>
                    <span wire:loading>Submitting...</span>
                </x-guest-button>
            </form>
        </div>
    @endif
</div>
