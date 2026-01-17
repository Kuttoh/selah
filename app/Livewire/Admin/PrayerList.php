<?php

namespace App\Livewire\Admin;

use App\Models\PrayerRequest;
use Livewire\Component;

class PrayerList extends Component
{
    public $prayers;

    public $selectedPrayer = null;

    public $showModal = false;

    public $filter = 'all';

    public function mount()
    {
        $this->loadPrayers();
    }

    public function loadPrayers()
    {
        $query = PrayerRequest::orderBy('is_prayed_for', 'asc')->orderBy('created_at', 'desc');

        if ($this->filter === 'unprayed') {
            $query->where('is_prayed_for', false);
        } elseif ($this->filter === 'prayed') {
            $query->where('is_prayed_for', true);
        }

        $this->prayers = $query->get();
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->loadPrayers();
    }

    public function show($id)
    {
        $this->selectedPrayer = PrayerRequest::find($id);
        $this->showModal = true;
    }

    public function markAsPrayed()
    {
        if ($this->selectedPrayer) {
            $this->selectedPrayer->update([
                'is_prayed_for' => true,
                'prayed_at' => now(),
            ]);
            $this->loadPrayers();
            $this->showModal = false;
            $this->selectedPrayer = null;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedPrayer = null;
    }

    public function render()
    {
        return view('livewire.admin.prayer-list');
    }
}
