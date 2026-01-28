<div>
    <div class="flex justify-end">
        <a href="{{ route('prayers.create') }}">
            <flux:button variant="primary" size="sm">Submit Prayer</flux:button>
        </a>
    </div>
    <div class="mb-4 flex justify-between items-center">
        <div class="flex space-x-2">
            <flux:button wire:click="setFilter('all')" :variant="$filter === 'all' ? 'primary' : 'ghost'" size="sm">All</flux:button>
            <flux:button wire:click="setFilter('unprayed')" :variant="$filter === 'unprayed' ? 'primary' : 'ghost'" size="sm">Unprayed</flux:button>
            <flux:button wire:click="setFilter('prayed')" :variant="$filter === 'prayed' ? 'primary' : 'ghost'" size="sm">Prayed</flux:button>
        </div>
    </div>

    @if ($prayers->count())
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse border border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-900">
                <thead class="bg-gray-50 dark:bg-zinc-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600">Prayer</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600">Name</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600">Submitted At</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($prayers as $prayer)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 {{ $prayer->status->value === 'prayed' ? 'opacity-50' : '' }}">
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">{{ Str::limit($prayer->prayer, 50) }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">{{ $prayer->name }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prayer->status->value === 'prayed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                    {{ $prayer->status->value === 'prayed' ? 'Prayed' : 'Pending' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">{{ $prayer->created_at->format('M j, Y') }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">
                                <flux:button wire:click="show({{ $prayer->id }})" variant="primary" size="sm">
                                    View
                                </flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $prayers->links() }}
        </div>
    @else
        <p class="text-gray-500 dark:text-gray-400">No prayer requests found.</p>
    @endif

    <flux:modal wire:model="showModal" class="md:w-96">
        @if ($selectedPrayer)
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Prayer Request</flux:heading>
                    @empty($selectedPrayer->name)
                        <flux:subheading>Submitted by <i>Anonymous</i></flux:subheading>
                    @else 
                        <flux:subheading>Submitted by {!! '<i>' . $selectedPrayer->name . '</i>' !!}</flux:subheading>
                    @endempty
                </div>

                    <div>
                        <flux:text>{!! nl2br(e($selectedPrayer->prayer)) !!}</flux:text>
                    </div>

                <div class="flex justify-between space-x-2">
                    <flux:button wire:click="closeModal" variant="ghost">Close</flux:button>
                    @if ($selectedPrayer->status->value === 'received')
                        <flux:button wire:click="markAsPrayed" variant="primary">Mark as Prayed</flux:button>
                    @endif
                </div>
            </div>
        @endif
    </flux:modal>
</div>