<?php

namespace App\Livewire\Admin;

use App\Enums\CallbackStatus;
use App\Models\Callback;
use App\Models\CallbackInteraction;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CallbackDetail extends Component
{
    public Callback $callback;

    public bool $showInteractionModal = false;

    public string $interactionNotes = '';

    public string $interactionStatus = '';

    public function mount(Callback $callback): void
    {
        $this->callback = $callback->load(['service', 'prayerRequest', 'interactions.createdBy']);
    }

    public function openInteractionModal(): void
    {
        $this->reset(['interactionNotes', 'interactionStatus']);
        $this->showInteractionModal = true;
    }

    public function addInteraction(): void
    {
        $this->validate([
            'interactionStatus' => ['required', 'string'],
            'interactionNotes' => ['nullable', 'string', 'max:1000'],
        ]);

        CallbackInteraction::create([
            'callback_id' => $this->callback->id,
            'notes' => $this->interactionNotes ?: null,
            'status' => $this->interactionStatus,
            'created_by' => Auth::id(),
        ]);

        $this->callback->load(['service', 'prayerRequest', 'interactions.createdBy']);
        $this->closeInteractionModal();
    }

    public function closeInteractionModal(): void
    {
        $this->showInteractionModal = false;
        $this->reset(['interactionNotes', 'interactionStatus']);
    }

    public function render(): View
    {
        return view('livewire.admin.callback-detail', [
            'statuses' => CallbackStatus::cases(),
            'interactions' => $this->callback->interactions()->with('createdBy')->latest('id')->get(),
        ]);
    }
}
