<?php

namespace App\Livewire\Admin;

use App\Models\Group;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class GroupList extends Component
{
    use WithPagination;

    public ?Group $selectedGroup = null;

    public bool $showModal = false;

    public bool $showConfirmModal = false;

    public ?int $confirmGroupId = null;

    public string $confirmActionType = '';

    public string $confirmTitle = '';

    public string $confirmMessage = '';

    public bool $isEditing = false;

    public string $filter = 'all';

    // Form fields
    public string $name = '';

    public string $description = '';

    public bool $active = true;

    public int $displayOrder = 0;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'active' => 'boolean',
            'displayOrder' => 'integer|min:0',
        ];
    }

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->reset(['name', 'description', 'active', 'displayOrder', 'isEditing', 'selectedGroup']);
        $this->active = true;
        $this->displayOrder = Group::max('display_order') + 1;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->selectedGroup = Group::findOrFail($id);
        $this->name = $this->selectedGroup->name;
        $this->description = $this->selectedGroup->description ?? '';
        $this->active = $this->selectedGroup->active;
        $this->displayOrder = $this->selectedGroup->display_order;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function handleAction(int $id, string $action): void
    {
        if ($action === 'edit') {
            $this->openEditModal($id);

            return;
        }

        if ($action === 'delete') {
            $this->confirmActionType = 'delete';
            $this->confirmTitle = __('Delete Group');
            $this->confirmMessage = __('Are you sure you want to delete this group?');
        }

        if ($action === 'toggle') {
            $group = Group::findOrFail($id);

            $this->confirmActionType = 'toggle';
            $this->confirmTitle = $group->active
                ? __('Deactivate Group')
                : __('Activate Group');
            $this->confirmMessage = $group->active
                ? __('Are you sure you want to deactivate this group?')
                : __('Are you sure you want to activate this group?');
        }

        if ($this->confirmActionType !== '') {
            $this->confirmGroupId = $id;
            $this->showConfirmModal = true;
        }
    }

    public function confirmAction(): void
    {
        if (! $this->confirmGroupId || $this->confirmActionType === '') {
            $this->closeConfirmModal();

            return;
        }

        if ($this->confirmActionType === 'delete') {
            $this->delete($this->confirmGroupId);
        }

        if ($this->confirmActionType === 'toggle') {
            $this->toggleActive($this->confirmGroupId);
        }

        $this->closeConfirmModal();
    }

    public function save(): void
    {
        $this->validate();

        if ($this->isEditing && $this->selectedGroup) {
            $this->selectedGroup->update([
                'name' => $this->name,
                'description' => $this->description ?: null,
                'active' => $this->active,
                'display_order' => $this->displayOrder,
            ]);
        } else {
            Group::create([
                'name' => $this->name,
                'description' => $this->description ?: null,
                'active' => $this->active,
                'display_order' => $this->displayOrder,
            ]);
        }

        $this->closeModal();
    }

    public function delete(int $id): void
    {
        $group = Group::findOrFail($id);
        $group->delete();
    }

    public function toggleActive(int $id): void
    {
        $group = Group::findOrFail($id);
        $group->update(['active' => ! $group->active]);
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['name', 'description', 'active', 'displayOrder', 'isEditing', 'selectedGroup']);
    }

    public function closeConfirmModal(): void
    {
        $this->showConfirmModal = false;
        $this->confirmGroupId = null;
        $this->confirmActionType = '';
        $this->confirmTitle = '';
        $this->confirmMessage = '';
    }

    public function render(): View
    {
        return view('livewire.admin.group-list', [
            'groups' => $this->groupsQuery()->paginate(10),
        ]);
    }

    private function groupsQuery(): Builder
    {
        $query = Group::query();

        if ($this->filter === 'active') {
            $query->active();
        } elseif ($this->filter === 'inactive') {
            $query->where('active', false);
        }

        return $query->orderBy('display_order')->orderBy('name');
    }
}
