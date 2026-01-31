<div>
    @if($submitted)
        <div class="flex flex-col items-center">
            <div class="">
                <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
            </div>
            <h1 class="font-medium text-lg text-green-700 mb-2">Callback Request Received 📞</h1>
            <p class="mb-4 text-center text-[#706f6c] dark:text-[#A1A09A]">
                Thank you for reaching out! We've received your callback request and will contact you soon.
            </p>
            <a
                href="{{ route('home') }}"
                class="px-5 py-2 bg-[#1b1b18] dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black border border-black text-white rounded-sm text-sm leading-normal"
            >
                Return Home
            </a>
        </div>
    @else
        <div class="flex flex-col items-center text-center mb-4">
            <div class="p-2">
                <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
            </div>
            <h1 class="mb-6 font-medium text-lg">Request a Callback</h1>
            <p class="text-[#706f6c] dark:text-[#A1A09A]">
                Share your contact details and we'll reach out to you 📞
            </p>
        </div>
        <div class="flex flex-col items-center text-center">
            <form wire:submit="submit" class="w-full max-w-md space-y-4">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium mb-2">Name</label>
                    <input
                        wire:model="name"
                        type="text"
                        id="name"
                        class="w-full px-3 py-2 border border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-600 focus:border-transparent"
                        placeholder="Your name"
                    />
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium mb-2">Phone Number</label>
                    <input
                        wire:model="phone"
                        type="tel"
                        id="phone"
                        class="w-full px-3 py-2 border border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-600 focus:border-transparent"
                        placeholder="0712345678 or +254712345678"
                    />
                    @error('phone')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="serviceId" class="block text-sm font-medium mb-2">Service (Optional)</label>
                    <select
                        wire:model="serviceId"
                        id="serviceId"
                        class="w-full px-3 py-2 border border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-600 focus:border-transparent"
                    >
                        <option value="">Select a service...</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                    @error('serviceId')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="w-full px-5 py-2 bg-[#1b1b18] dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black border border-black text-white rounded-sm text-sm leading-normal disabled:opacity-50"
                >
                    <span wire:loading.remove>Request Callback</span>
                    <span wire:loading>Submitting...</span>
                </button>
            </form>
        </div>
    @endif
</div>
