<?php

namespace App\Livewire\Admin;

use App\Models\PrayerRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
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
            $this->selectedPrayer->update([
                'is_prayed_for' => true,
                'prayed_at' => now(),
            ]);

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
            $query->where('is_prayed_for', false);
        } elseif ($this->filter === 'prayed') {
            $query->where('is_prayed_for', true);
        }

        return $query
            ->orderBy('is_prayed_for')
            ->orderByDesc('created_at');
    }
}
