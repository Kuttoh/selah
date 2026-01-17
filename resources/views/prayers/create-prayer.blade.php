<div>
    @if($submitted)
        <div class="flex flex-col items-center mb-6">
                <div class="">
                    <img src="/favicon-selah.svg" alt="Selah" class="size-9 mb-4 mx-auto fill-current dark:text-black" />
                </div>
                <h1 class="mb-4 font-medium text-lg">Prayer Submitted</h1>
            <p class="text-green-400 font-medium mb-2">Thank you for sharing your prayer 🙌🏾</p>
            <p class="text-green-400 font-medium">It has been received with care and we will pray with you!</p>
        </div>
    @else
        <div class="mb-6 flex flex-col items-center text-center">
            <div class="p-2 mb-4">
                <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
            </div>
            <h1 class="mb-6 font-medium text-lg">Share Yours Prayer</h1>
            <p class="text-[#706f6c] dark:text-[#A1A09A]">Take a moment to share your prayer 🤍, your words are heard and valued in this sacred space &#x1F64F;</p>
        </div>
        <div class="flex flex-col items-center text-center">
            <form wire:submit="submit" class="w-full max-w-md space-y-4">
                <div class="mb-4">
                    <label for="prayer" class="block text-sm font-medium mb-6">Your Prayer</label>
                    <textarea
                        wire:model="prayer"
                        id="prayer"
                        rows="10"
                        class="w-full border box-border px-1 py-2 border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-600 focus:border-transparent"
                        placeholder="Share your prayer here..."
                    ></textarea>
                    @error('prayer')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium mb-6">Name (Optional)</label>
                    <input
                        wire:model="name"
                        type="text"
                        id="name"
                        class="w-full px-1 py-2 border border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-600 focus:border-transparent"
                        placeholder="Your name"
                    />
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="w-full px-5 py-2 bg-[#1b1b18] dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black border border-black text-white rounded-sm text-sm leading-normal disabled:opacity-50"
                >
                    <span wire:loading.remove>Submit Prayer</span>
                    <span wire:loading>Submitting...</span>
                </button>
            </form>
        </div>
    @endif
</div>
