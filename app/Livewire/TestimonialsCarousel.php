<?php

namespace App\Livewire;

use App\Models\Testimonial;
use Illuminate\Support\Collection;
use Livewire\Component;

class TestimonialsCarousel extends Component
{
    public Collection $testimonials;

    public function mount(): void
    {
        $this->testimonials = Testimonial::approved()
            ->latest()
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.testimonials-carousel');
    }
}
