<?php

namespace App\Livewire;

use App\Enums\PrayerStatus;
use App\Models\PrayerRequest;
use App\Models\Testimonial;
use Livewire\Component;

class MarkPrayerAnswered extends Component
{
    public PrayerRequest $prayer;

    public bool $showModal = false;

    public bool $submitted = false;

    public string $testimonialContent = '';

    public string $displayName = '';

    public bool $isPublic = false;

    protected function rules(): array
    {
        return [
            'testimonialContent' => 'nullable|string|max:2000',
            'displayName' => 'nullable|string|max:255',
            'isPublic' => 'boolean',
        ];
    }

    public function mount(string $publicToken): void
    {
        $this->prayer = PrayerRequest::query()
            ->where('public_token', $publicToken)
            ->firstOrFail();
    }

    public function openModal(): void
    {
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['testimonialContent', 'displayName', 'isPublic']);
    }

    public function markAsAnswered(): void
    {
        $this->validate();

        $this->prayer->update([
            'status' => PrayerStatus::Answered,
            'answered_at' => now(),
        ]);

        if ($this->testimonialContent !== '') {
            Testimonial::create([
                'prayer_request_id' => $this->prayer->id,
                'content' => $this->testimonialContent,
                'display_name' => $this->displayName ?: null,
                'is_public' => $this->isPublic,
                'is_approved' => false,
            ]);
        }

        $this->submitted = true;
        $this->showModal = false;
    }

    public function render()
    {
        return view('prayers.mark-prayer-answered');
    }
}
