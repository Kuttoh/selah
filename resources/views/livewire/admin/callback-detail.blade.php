<div class="space-y-6">
    {{-- Header with back button and actions --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <flux:button variant="ghost" icon="arrow-left" href="{{ route('admin.callbacks') }}" wire:navigate />
            <div>
                <flux:heading size="xl">{{ $callback->name }}</flux:heading>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $callback->phone }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                @switch($callback->current_status->value)
                    @case('pending')
                        bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                        @break
                    @case('called')
                        bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                        @break
                    @case('no_answer')
                        bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                        @break
                    @case('follow_up')
                        bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                        @break
                    @case('completed')
                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @break
                    @case('closed')
                        bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                        @break
                    @default
                        bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                @endswitch
            ">
                {{ ucfirst(str_replace('_', ' ', $callback->current_status->value)) }}
            </span>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Left Column: Details Card --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <flux:heading size="lg" class="mb-4">{{ __('Callback Details') }}</flux:heading>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('Name') }}</p>
                        <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $callback->name }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('Phone') }}</p>
                        <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $callback->phone }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('Service') }}</p>
                        <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $callback->service?->name ?? '-' }}</p>
                    </div>

                    @if($callback->prayerRequest)
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                            <p class="text-xs font-medium text-blue-800 dark:text-blue-200 uppercase tracking-wide">{{ __('Linked Prayer Request') }}</p>
                            <p class="text-sm text-blue-600 dark:text-blue-300 mt-1">{{ Str::limit($callback->prayerRequest->prayer, 100) }}</p>
                            <a href="{{ route('prayers.progress', $callback->prayerRequest->public_token) }}" target="_blank" class="text-xs text-blue-500 hover:underline mt-1 inline-block">
                                {{ __('View Progress Page') }} →
                            </a>
                        </div>
                    @endif
                </div>

                <flux:separator class="my-6" />

                <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                    <p>{{ __('Created') }}: {{ $callback->created_at->format('M d, Y \a\t g:i A') }}</p>
                    <p>{{ __('Updated') }}: {{ $callback->updated_at->format('M d, Y \a\t g:i A') }}</p>
                </div>
            </div>
        </div>

        {{-- Right Column: Interactions --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <flux:heading size="lg">{{ __('Interaction History') }}</flux:heading>
                    <flux:button wire:click="openInteractionModal" variant="primary" icon="plus">
                        {{ __('Log Interaction') }}
                    </flux:button>
                </div>

                @if($interactions->count())
                    <div class="space-y-4">
                        @foreach($interactions as $interaction)
                            <div wire:key="interaction-{{ $interaction->id }}" class="p-4 bg-gray-50 dark:bg-zinc-900 rounded-lg border border-gray-200 dark:border-gray-700">
                                <div class="flex items-start justify-between mb-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        @switch($interaction->status->value)
                                            @case('pending')
                                                bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @break
                                            @case('called')
                                                bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @break
                                            @case('no_answer')
                                                bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                                @break
                                            @case('follow_up')
                                                bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                                @break
                                            @case('completed')
                                                bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @break
                                            @case('closed')
                                                bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                                @break
                                            @default
                                                bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                        @endswitch
                                    ">
                                        {{ ucfirst(str_replace('_', ' ', $interaction->status->value)) }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $interaction->created_at->format('M d, Y \a\t g:i A') }}
                                    </span>
                                </div>
                                @if($interaction->notes)
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mt-2">{{ $interaction->notes }}</p>
                                @else
                                    <p class="text-sm text-gray-400 dark:text-gray-500 italic mt-2">{{ __('No notes provided') }}</p>
                                @endif
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                    {{ __('Logged by') }}: {{ $interaction->createdBy?->name ?? __('System') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                        <flux:icon name="chat-bubble-left-right" class="size-12 mx-auto mb-4 opacity-50" />
                        <p class="text-lg font-medium">{{ __('No interactions yet') }}</p>
                        <p class="text-sm mt-1">{{ __('Log your first interaction to start tracking progress.') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Add Interaction Modal --}}
    <flux:modal wire:model="showInteractionModal" class="md:w-[500px]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Log Interaction') }}</flux:heading>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Record an interaction with') }} {{ $callback->name }}</p>
            </div>

            <form wire:submit="addInteraction" class="space-y-4">
                <flux:field>
                    <flux:label>{{ __('Status') }}</flux:label>
                    <flux:select wire:model="interactionStatus">
                        <flux:select.option value="">{{ __('Select status') }}</flux:select.option>
                        @foreach($statuses as $status)
                            <flux:select.option value="{{ $status->value }}">{{ ucfirst(str_replace('_', ' ', $status->value)) }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="interactionStatus" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Notes (Optional)') }}</flux:label>
                    <flux:textarea wire:model="interactionNotes" placeholder="{{ __('Add notes about this interaction...') }}" rows="4" />
                    <flux:error name="interactionNotes" />
                </flux:field>

                <div class="flex justify-end gap-2 pt-4">
                    <flux:button wire:click="closeInteractionModal" variant="ghost">{{ __('Cancel') }}</flux:button>
                    <flux:button type="submit" variant="primary">{{ __('Log Interaction') }}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

</div>
