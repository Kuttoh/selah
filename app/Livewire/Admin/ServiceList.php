<?php

namespace App\Livewire\Admin;

use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceList extends Component
{
    use WithPagination;

    public ?Service $selectedService = null;

    public bool $showModal = false;

    public bool $showConfirmModal = false;

    public ?int $confirmServiceId = null;

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
        $this->reset(['name', 'description', 'active', 'displayOrder', 'isEditing', 'selectedService']);
        $this->active = true;
        $this->displayOrder = Service::max('display_order') + 1;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->selectedService = Service::findOrFail($id);
        $this->name = $this->selectedService->name;
        $this->description = $this->selectedService->description ?? '';
        $this->active = $this->selectedService->active;
        $this->displayOrder = $this->selectedService->display_order;
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
            $this->confirmTitle = __('Delete Service');
            $this->confirmMessage = __('Are you sure you want to delete this service?');
        }

        if ($action === 'toggle') {
            $service = Service::findOrFail($id);

            $this->confirmActionType = 'toggle';
            $this->confirmTitle = $service->active
                ? __('Deactivate Service')
                : __('Activate Service');
            $this->confirmMessage = $service->active
                ? __('Are you sure you want to deactivate this service?')
                : __('Are you sure you want to activate this service?');
        }

        if ($this->confirmActionType !== '') {
            $this->confirmServiceId = $id;
            $this->showConfirmModal = true;
        }
    }

    public function confirmAction(): void
    {
        if (! $this->confirmServiceId || $this->confirmActionType === '') {
            $this->closeConfirmModal();

            return;
        }

        if ($this->confirmActionType === 'delete') {
            $this->delete($this->confirmServiceId);
        }

        if ($this->confirmActionType === 'toggle') {
            $this->toggleActive($this->confirmServiceId);
        }

        $this->closeConfirmModal();
    }

    public function save(): void
    {
        $this->validate();

        if ($this->isEditing && $this->selectedService) {
            $this->selectedService->update([
                'name' => $this->name,
                'description' => $this->description ?: null,
                'active' => $this->active,
                'display_order' => $this->displayOrder,
            ]);
        } else {
            Service::create([
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
        $service = Service::findOrFail($id);
        $service->delete();
    }

    public function toggleActive(int $id): void
    {
        $service = Service::findOrFail($id);
        $service->update(['active' => ! $service->active]);
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['name', 'description', 'active', 'displayOrder', 'isEditing', 'selectedService']);
    }

    public function closeConfirmModal(): void
    {
        $this->showConfirmModal = false;
        $this->confirmServiceId = null;
        $this->confirmActionType = '';
        $this->confirmTitle = '';
        $this->confirmMessage = '';
    }

    public function render(): View
    {
        return view('livewire.admin.service-list', [
            'services' => $this->servicesQuery()->paginate(10),
        ]);
    }

    private function servicesQuery(): Builder
    {
        $query = Service::query();

        if ($this->filter === 'active') {
            $query->active();
        } elseif ($this->filter === 'inactive') {
            $query->where('active', false);
        }

        return $query->orderBy('display_order')->orderBy('name');
    }
}
