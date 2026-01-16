<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PrayerRequest;

class CreatePrayer extends Component
{
    public $prayer = '';
    public $name = '';
    public $submitted = false;

    protected $rules = [
        'prayer' => 'required|string|min:1',
        'name' => 'nullable|string',
    ];

    public function submit()
    {
        $this->validate();

        PrayerRequest::create([
            'prayer' => $this->prayer,
            'name' => $this->name ?: null,
            'is_prayed_for' => false,
        ]);

        $this->submitted = true;
        $this->reset(['prayer', 'name']);
    }

    public function render()
    {
        return view('prayers.create-prayer');
    }
}
