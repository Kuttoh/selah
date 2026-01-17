<?php

namespace App\Livewire\Admin;

use App\Models\PrayerRequest;
use Livewire\Component;

class PrayerList extends Component
{
    public $prayers;

    public $selectedPrayer = null;

    public $showModal = false;

    public function mount()
    {
        $this->loadPrayers();
    }

    public function loadPrayers()
    {
        $this->prayers = PrayerRequest::orderBy('created_at', 'desc')->get();
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
