<div>
    <div class="flex justify-between items-center mb-4">
        <div class="w-64">
            <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Search by name or phone...') }}" />
        </div>
        <flux:button wire:click="openCreateModal" variant="primary" size="sm">{{ __('Add Callback') }}</flux:button>
    </div>

    <div class="mb-4">
        <div class="w-48">
            <flux:select wire:model.live="filter">
                <flux:select.option value="all">{{ __('All Statuses') }}</flux:select.option>
                @foreach($statuses as $status)
                    <flux:select.option value="{{ $status->value }}">{{ ucfirst(str_replace('_', ' ', $status->value)) }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>
    </div>

    @if ($callbacks->count())
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse border border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-900">
                <thead class="bg-gray-50 dark:bg-zinc-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600">{{ __('Name') }}</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600">{{ __('Phone') }}</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600">{{ __('Status') }}</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600">{{ __('Prayer Request') }}</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600">{{ __('Created') }}</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($callbacks as $callback)
                        <tr wire:key="callback-{{ $callback->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">{{ $callback->name }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">{{ $callback->phone }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">
                                @php $status = $callback->current_status; @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @switch($status->value)
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
                                    {{ ucfirst(str_replace('_', ' ', $status->value)) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">
                                @if($callback->prayerRequest)
                                    <a href="{{ route('prayers.progress', $callback->prayerRequest->public_token) }}" target="_blank" class="text-blue-600 hover:underline dark:text-blue-400">
                                        {{ Str::limit($callback->prayerRequest->prayer, 30) }}
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">{{ $callback->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">
                                <flux:dropdown align="end">
                                    <flux:button variant="ghost" size="sm" icon-trailing="chevron-down">
                                        {{ __('Actions') }}
                                    </flux:button>

                                    <flux:menu>
                                        <flux:menu.item href="{{ route('admin.callbacks.show', $callback) }}" wire:navigate>
                                            {{ __('View') }}
                                        </flux:menu.item>
                                        <flux:menu.item wire:click="openLogInteractionModal({{ $callback->id }})">
                                            {{ __('Log Interaction') }}
                                        </flux:menu.item>
                                        <flux:menu.separator />
                                        <flux:menu.item wire:click="confirmDelete({{ $callback->id }})">
                                            {{ __('Delete') }}
                                        </flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $callbacks->links() }}
        </div>
    @else
        <p class="text-gray-500 dark:text-gray-400">{{ __('No callback requests found.') }}</p>
    @endif

    {{-- Create Modal --}}
    <flux:modal wire:model="showModal" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Add Callback') }}</flux:heading>
            </div>

            <form wire:submit="save" class="space-y-4">
                <flux:input
                    wire:model="name"
                    label="{{ __('Name') }}"
                    placeholder="{{ __('Enter name') }}"
                    required
                />

                <flux:input
                    wire:model="phone"
                    label="{{ __('Phone Number') }}"
                    placeholder="{{ __('0712345678 or +254712345678') }}"
                    required
                />

                <flux:select wire:model="serviceId" label="{{ __('Service (Optional)') }}">
                    <flux:select.option value="">{{ __('Select a service...') }}</flux:select.option>
                    @foreach($services as $service)
                        <flux:select.option value="{{ $service->id }}">{{ $service->name }}</flux:select.option>
                    @endforeach
                </flux:select>

                <div>
                    <flux:label>{{ __('Prayer Request (Optional)') }}</flux:label>
                    @if($prayerRequestId)
                        <div class="flex items-center gap-2 mt-1 p-2 bg-gray-100 dark:bg-zinc-800 rounded">
                            <span class="text-sm flex-1">{{ $prayerRequestSearch }}</span>
                            <flux:button wire:click="clearPrayerRequest" variant="ghost" size="xs">{{ __('Clear') }}</flux:button>
                        </div>
                    @else
                        <flux:input
                            wire:model.live.debounce.300ms="prayerRequestSearch"
                            placeholder="{{ __('Search by name or prayer text...') }}"
                        />
                        @if(count($prayerRequests) > 0)
                            <div class="mt-1 border border-gray-200 dark:border-gray-700 rounded bg-white dark:bg-zinc-900 max-h-40 overflow-y-auto">
                                @foreach($prayerRequests as $pr)
                                    <button
                                        type="button"
                                        wire:click="selectPrayerRequest({{ $pr->id }})"
                                        class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-zinc-800"
                                    >
                                        <span class="font-medium">{{ $pr->name ?? 'Anonymous' }}</span>
                                        <span class="text-gray-500 dark:text-gray-400 block text-xs">{{ Str::limit($pr->prayer, 50) }}</span>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>

                <div class="flex justify-between pt-4">
                    <flux:button wire:click="closeModal" variant="ghost">{{ __('Cancel') }}</flux:button>
                    <flux:button type="submit" variant="primary">{{ __('Create') }}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    {{-- Log Interaction Modal --}}
    <flux:modal wire:model="showLogModal" class="md:w-[500px]">
        @if($selectedCallback)
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Log Interaction') }}</flux:heading>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Record an interaction with') }} {{ $selectedCallback->name }}</p>
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
                        <flux:button wire:click="closeLogModal" variant="ghost">{{ __('Cancel') }}</flux:button>
                        <flux:button type="submit" variant="primary">{{ __('Log Interaction') }}</flux:button>
                    </div>
                </form>
            </div>
        @endif
    </flux:modal>

    {{-- Confirm Delete Modal --}}
    <flux:modal wire:model="showConfirmModal" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Delete Callback') }}</flux:heading>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ __('Are you sure you want to delete this callback request? This action cannot be undone.') }}</p>
            </div>

            <div class="flex justify-end gap-2">
                <flux:button wire:click="closeConfirmModal" variant="ghost">{{ __('Cancel') }}</flux:button>
                <flux:button wire:click="delete" variant="danger">{{ __('Delete') }}</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
