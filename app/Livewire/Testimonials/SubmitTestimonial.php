<?php

namespace App\Livewire\Testimonials;

use App\Models\Testimonial;
use Livewire\Component;

class SubmitTestimonial extends Component
{
    public string $content = '';

    public string $displayName = '';

    public bool $showModal = false;

    public bool $submitted = false;

    protected function rules(): array
    {
        return [
            'content' => 'required|string|max:2000',
            'displayName' => 'nullable|string|max:255',
        ];
    }

    public function openModal(): void
    {
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['content', 'displayName']);
    }

    public function submit(): void
    {
        $this->validate();

        Testimonial::create([
            'content' => $this->content,
            'display_name' => $this->displayName ?: null,
            'is_public' => true,
            'is_approved' => false,
            'prayer_request_id' => null,
        ]);

        $this->submitted = true;
        $this->showModal = false;
        $this->reset(['content', 'displayName']);
    }

    public function render()
    {
        return view('testimonials.submit-testimonial');
    }
}
