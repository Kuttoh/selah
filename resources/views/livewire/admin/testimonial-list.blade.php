<div>
    <div class="flex justify-end mb-4">
        <flux:button wire:click="openCreateModal" variant="primary" size="sm">{{ __('Submit Testimonial') }}</flux:button>
    </div>
    <div class="mb-4">
        <div class="w-40">
            <flux:select wire:model.live="filter">
                <flux:select.option value="all">{{ __('All') }}</flux:select.option>
                <flux:select.option value="pending">{{ __('Pending') }}</flux:select.option>
                <flux:select.option value="approved">{{ __('Approved') }}</flux:select.option>
                <flux:select.option value="private">{{ __('Private') }}</flux:select.option>
            </flux:select>
        </div>
    </div>

    @if ($testimonials->count())
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse border border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-900">
                <thead class="bg-gray-50 dark:bg-zinc-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600">{{ __('Testimonial') }}</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600">{{ __('Display Name') }}</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600">{{ __('Status') }}</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600">{{ __('Submitted') }}</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($testimonials as $testimonial)
                        <tr wire:key="testimonial-{{ $testimonial->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700 {{ $testimonial->is_approved ? 'opacity-50' : '' }}">
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">{{ Str::limit($testimonial->content, 50) }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">{{ $testimonial->display_name ?? 'Anonymous' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">
                                @if ($testimonial->is_approved)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        {{ __('Approved') }}
                                    </span>
                                @elseif ($testimonial->is_public)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        {{ __('Pending') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                        {{ __('Private') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">{{ $testimonial->created_at->format('M j, Y') }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">
                                <flux:button wire:click="show({{ $testimonial->id }})" variant="primary" size="sm">
                                    {{ __('View') }}
                                </flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $testimonials->links() }}
        </div>
    @else
        <p class="text-gray-500 dark:text-gray-400">{{ __('No testimonials found.') }}</p>
    @endif

    <flux:modal wire:model="showModal" class="md:w-96">
        @if ($selectedTestimonial)
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Testimonial') }}</flux:heading>
                    <flux:subheading>
                        {{ __('From') }}
                        <em>{{ $selectedTestimonial->display_name ?? __('Anonymous') }}</em>
                    </flux:subheading>
                </div>

                <div>
                    <flux:text class="text-xs italic">{!! nl2br(e($selectedTestimonial->content)) !!}</flux:text>
                </div>

                @if ($selectedTestimonial->prayerRequest)
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
                        <flux:subheading class="mb-2">{{ __('Associated Prayer') }}</flux:subheading>
                        <flux:text class="text-xs italic">
                            {{ Str::limit($selectedTestimonial->prayerRequest->prayer, 200) }}
                        </flux:text>
                    </div>
                @endif

                <div class="flex justify-between space-x-2 mt-2">
                    {{-- <flux:button wire:click="closeModal" variant="ghost">{{ __('Close') }}</flux:button> --}}
                    @if ($selectedTestimonial->is_public && !$selectedTestimonial->is_approved)
                        <flux:button wire:click="reject" variant="danger">{{ __('Reject') }}</flux:button>
                        <flux:button wire:click="approve" variant="primary">{{ __('Approve') }}</flux:button>
                    @endif
                </div>
            </div>
        @endif
    </flux:modal>

    <flux:modal wire:model="showCreateModal" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Submit Testimonial') }}</flux:heading>
                <flux:text class="mt-2">{{ __('Add a testimonial that is not associated with a prayer request.') }}</flux:text>
            </div>

            <form wire:submit="createTestimonial" class="space-y-4">
                <flux:textarea
                    wire:model="newContent"
                    label="{{ __('Testimonial') }}"
                    placeholder="{{ __('Enter the testimonial content...') }}"
                    rows="4"
                    required
                />

                <flux:input
                    wire:model="newDisplayName"
                    label="{{ __('Display Name (Optional)') }}"
                    placeholder="{{ __('Enter the display name (optional)') }}"
                />

                <flux:field variant="inline">
                    <flux:checkbox wire:model="newIsPublic" />
                    <flux:label>{{ __('Public') }}</flux:label>
                </flux:field>

                <flux:field variant="inline">
                    <flux:checkbox wire:model="newIsApproved" />
                    <flux:label>{{ __('Pre-approved') }}</flux:label>
                </flux:field>

                <div class="flex justify-between pt-4">
                    <flux:button wire:click="closeCreateModal" variant="ghost">{{ __('Cancel') }}</flux:button>
                    <flux:button type="submit" variant="primary">{{ __('Submit') }}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
