<div>
    <div class="flex justify-end mb-4">
        <flux:button wire:click="openCreateModal" variant="primary" size="sm">{{ __('Add Group') }}</flux:button>
    </div>
    <div class="mb-4">
        <div class="w-40">
            <flux:select wire:model.live="filter">
                <flux:select.option value="all">{{ __('All') }}</flux:select.option>
                <flux:select.option value="active">{{ __('Active') }}</flux:select.option>
                <flux:select.option value="inactive">{{ __('Inactive') }}</flux:select.option>
            </flux:select>
        </div>
    </div>

    @if ($groups->count())
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse border border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-900">
                <thead class="bg-gray-50 dark:bg-zinc-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600">{{ __('Order') }}</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600">{{ __('Name') }}</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600">{{ __('Description') }}</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600">{{ __('Status') }}</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groups as $group)
                        <tr wire:key="group-{{ $group->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">{{ $group->display_order }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">{{ $group->name }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">{{ Str::limit($group->description ?? '', 50) }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">
                                @if ($group->active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        {{ __('Active') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                        {{ __('Inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">
                                <flux:dropdown align="end">
                                    <flux:button variant="ghost" size="sm" icon-trailing="chevron-down">
                                        {{ __('Actions') }}
                                    </flux:button>

                                    <flux:menu>
                                        <flux:menu.item wire:click="handleAction({{ $group->id }}, 'edit')">
                                            {{ __('Edit') }}
                                        </flux:menu.item>
                                        <flux:menu.item wire:click="handleAction({{ $group->id }}, 'toggle')">
                                            {{ $group->active ? __('Deactivate') : __('Activate') }}
                                        </flux:menu.item>
                                        <flux:menu.separator />
                                        <flux:menu.item wire:click="handleAction({{ $group->id }}, 'delete')" class="text-red-500" variant="danger">
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
            {{ $groups->links() }}
        </div>
    @else
        <p class="text-gray-500 dark:text-gray-400">{{ __('No groups found.') }}</p>
    @endif

    <flux:modal wire:model="showModal" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $isEditing ? __('Edit Group') : __('Add Group') }}</flux:heading>
            </div>

            <form wire:submit="save" class="space-y-4">
                <flux:input
                    wire:model="name"
                    label="{{ __('Name') }}"
                    placeholder="{{ __('Enter group name') }}"
                    required
                />

                <flux:textarea
                    wire:model="description"
                    label="{{ __('Description') }}"
                    placeholder="{{ __('Enter group description (optional)') }}"
                    rows="3"
                />

                <flux:input
                    wire:model="displayOrder"
                    type="number"
                    label="{{ __('Display Order') }}"
                    placeholder="{{ __('Enter display order') }}"
                    min="0"
                    required
                />

                <flux:field variant="inline">
                    <flux:checkbox wire:model="active" />
                    <flux:label>{{ __('Active') }}</flux:label>
                </flux:field>

                <div class="flex justify-between pt-4">
                    <flux:button wire:click="closeModal" variant="ghost">{{ __('Cancel') }}</flux:button>
                    <flux:button type="submit" variant="primary">{{ $isEditing ? __('Update') : __('Create') }}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <flux:modal wire:model="showConfirmModal" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $confirmTitle }}</flux:heading>
                <flux:text class="mt-2">{{ $confirmMessage }}</flux:text>
            </div>

            <div class="flex justify-between">
                <flux:button wire:click="closeConfirmModal" variant="ghost">{{ __('Cancel') }}</flux:button>
                <flux:button wire:click="confirmAction" variant="danger">{{ __('Confirm') }}</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
