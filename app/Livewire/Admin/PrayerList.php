<?php

namespace App\Livewire\Admin;

use App\Enums\PrayerStatus;
use App\Models\PrayerRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PrayerList extends Component
{
    use WithPagination;

    public ?PrayerRequest $selectedPrayer = null;

    public bool $showModal = false;

    public string $filter = 'all';

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function show(int $id): void
    {
        $this->selectedPrayer = PrayerRequest::find($id);
        $this->showModal = true;
    }

    public function markAsPrayed(): void
    {
        if ($this->selectedPrayer) {
            $data = [
                'status' => PrayerStatus::Prayed,
                'prayed_at' => now(),
            ];

            if (is_null($this->selectedPrayer->prayed_by)) {
                $data['prayed_by'] = Auth::id();
            }

            $this->selectedPrayer->update($data);

            $this->showModal = false;
            $this->selectedPrayer = null;
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedPrayer = null;
    }

    public function render(): View
    {
        return view('livewire.admin.prayer-list', [
            'prayers' => $this->prayersQuery()->paginate(10),
        ]);
    }

    private function prayersQuery(): Builder
    {
        $query = PrayerRequest::query();

        if ($this->filter === 'unprayed') {
            $query->where('status', PrayerStatus::Received);
        } elseif ($this->filter === 'prayed') {
            $query->where('status', PrayerStatus::Prayed);
        }

        return $query
            ->orderByRaw('CASE WHEN status = ? THEN 0 ELSE 1 END', [PrayerStatus::Received->value])
            ->orderByDesc('created_at');
    }
}
