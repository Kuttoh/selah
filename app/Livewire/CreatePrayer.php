<?php

namespace App\Livewire;

use App\Enums\PrayerStatus;
use App\Models\PrayerRequest;
use Illuminate\Support\Str;
use Livewire\Component;

class CreatePrayer extends Component
{
    public $prayer = '';

    public $name = '';

    public $submitted = false;

    public $publicToken = null;

    protected $rules = [
        'prayer' => 'required|string|min:1',
        'name' => 'nullable|string',
    ];

    public function submit()
    {
        $this->validate();

        $prayer = PrayerRequest::create([
            'prayer' => $this->prayer,
            'name' => $this->name ?: null,
            'status' => PrayerStatus::Received,
            'public_token' => Str::uuid()->toString(),
        ]);

        $this->publicToken = $prayer->public_token;
        $this->submitted = true;
        $this->reset(['prayer', 'name']);
    }

    public function render()
    {
        return view('prayers.create-prayer');
    }
}
