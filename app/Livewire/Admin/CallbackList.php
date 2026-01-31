<?php

namespace App\Livewire\Admin;

use App\Enums\CallbackStatus;
use App\Models\Callback;
use App\Models\CallbackInteraction;
use App\Models\PrayerRequest;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class CallbackList extends Component
{
    use WithPagination;

    public bool $showModal = false;

    public bool $showLogModal = false;

    public bool $showConfirmModal = false;

    public ?int $confirmCallbackId = null;

    public ?Callback $selectedCallback = null;

    public string $filter = 'all';

    public string $search = '';

    // Form fields for create callback
    public string $name = '';

    public string $phone = '';

    public ?int $serviceId = null;

    public ?int $prayerRequestId = null;

    public string $prayerRequestSearch = '';

    // Form fields for log interaction
    public string $interactionStatus = '';

    public string $interactionNotes = '';

    /**
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^(?:\+254|0)[17]\d{8}$/'],
            'serviceId' => ['nullable', 'exists:services,id'],
            'prayerRequestId' => ['nullable', 'exists:prayer_requests,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'phone.regex' => 'Please enter a valid Kenyan phone number (e.g., 0712345678 or +254712345678).',
        ];
    }

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->reset(['name', 'phone', 'serviceId', 'prayerRequestId', 'prayerRequestSearch']);
        $this->showModal = true;
    }

    public function openLogInteractionModal(int $id): void
    {
        $this->selectedCallback = Callback::findOrFail($id);
        $this->reset(['interactionStatus', 'interactionNotes']);
        $this->showLogModal = true;
    }

    public function addInteraction(): void
    {
        $this->validate([
            'interactionStatus' => ['required', 'string'],
            'interactionNotes' => ['nullable', 'string', 'max:1000'],
        ]);

        if (! $this->selectedCallback) {
            return;
        }

        CallbackInteraction::create([
            'callback_id' => $this->selectedCallback->id,
            'notes' => $this->interactionNotes ?: null,
            'status' => $this->interactionStatus,
            'created_by' => Auth::id(),
        ]);

        $this->closeLogModal();
    }

    public function closeLogModal(): void
    {
        $this->showLogModal = false;
        $this->selectedCallback = null;
        $this->reset(['interactionStatus', 'interactionNotes']);
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmCallbackId = $id;
        $this->showConfirmModal = true;
    }

    public function delete(): void
    {
        if ($this->confirmCallbackId) {
            Callback::find($this->confirmCallbackId)?->delete();
        }

        $this->closeConfirmModal();
    }

    public function closeConfirmModal(): void
    {
        $this->showConfirmModal = false;
        $this->confirmCallbackId = null;
    }

    public function save(): void
    {
        $this->validate();

        Callback::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'service_id' => $this->serviceId ?: null,
            'prayer_request_id' => $this->prayerRequestId ?: null,
            'public_token' => Str::uuid()->toString(),
        ]);

        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['name', 'phone', 'serviceId', 'prayerRequestId', 'prayerRequestSearch']);
    }

    public function selectPrayerRequest(int $id): void
    {
        $prayerRequest = PrayerRequest::find($id);
        if ($prayerRequest) {
            $this->prayerRequestId = $prayerRequest->id;
            $this->prayerRequestSearch = $prayerRequest->name ?? 'Prayer #'.$prayerRequest->id;
        }
    }

    public function clearPrayerRequest(): void
    {
        $this->prayerRequestId = null;
        $this->prayerRequestSearch = '';
    }

    public function render(): View
    {
        $prayerRequests = [];
        if (strlen($this->prayerRequestSearch) >= 2 && ! $this->prayerRequestId) {
            $prayerRequests = PrayerRequest::query()
                ->where('name', 'like', '%'.$this->prayerRequestSearch.'%')
                ->orWhere('prayer', 'like', '%'.$this->prayerRequestSearch.'%')
                ->limit(5)
                ->get();
        }

        return view('livewire.admin.callback-list', [
            'callbacks' => $this->callbacksQuery()->paginate(10),
            'services' => Service::query()->active()->orderBy('name')->get(),
            'statuses' => CallbackStatus::cases(),
            'prayerRequests' => $prayerRequests,
        ]);
    }

    private function callbacksQuery(): Builder
    {
        $query = Callback::query()->with(['service', 'prayerRequest', 'interactions']);

        if ($this->search) {
            $query->where(function (Builder $q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('phone', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->filter !== 'all') {
            // Filter by current status (latest interaction status or pending if none)
            $query->where(function (Builder $q) {
                if ($this->filter === 'pending') {
                    // Callbacks with no interactions are pending
                    $q->whereDoesntHave('interactions');
                } else {
                    // Callbacks where the latest interaction has this status
                    $q->whereHas('interactions', function (Builder $subQ) {
                        $subQ->where('id', function ($innerQ) {
                            $innerQ->selectRaw('MAX(id)')
                                ->from('callback_interactions')
                                ->whereColumn('callback_id', 'callbacks.id');
                        })->where('status', $this->filter);
                    });
                }
            });
        }

        return $query->latest();
    }
}
