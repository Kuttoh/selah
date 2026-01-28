<?php

namespace App\Livewire\Admin;

use App\Models\Testimonial;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class TestimonialList extends Component
{
    use WithPagination;

    public ?Testimonial $selectedTestimonial = null;

    public bool $showModal = false;

    public bool $showCreateModal = false;

    public string $filter = 'all';

    // Create form fields
    public string $newContent = '';

    public string $newDisplayName = '';

    public bool $newIsPublic = true;

    public bool $newIsApproved = true;

    protected function rules(): array
    {
        return [
            'newContent' => 'required|string|max:2000',
            'newDisplayName' => 'nullable|string|max:255',
            'newIsPublic' => 'boolean',
            'newIsApproved' => 'boolean',
        ];
    }

    public function openCreateModal(): void
    {
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->reset(['newContent', 'newDisplayName', 'newIsPublic', 'newIsApproved']);
        $this->newIsPublic = true;
        $this->newIsApproved = true;
    }

    public function createTestimonial(): void
    {
        $this->validate();

        Testimonial::create([
            'content' => $this->newContent,
            'display_name' => $this->newDisplayName ?: null,
            'is_public' => $this->newIsPublic,
            'is_approved' => $this->newIsApproved,
            'prayer_request_id' => null,
        ]);

        $this->closeCreateModal();
    }

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function show(int $id): void
    {
        $this->selectedTestimonial = Testimonial::with('prayerRequest')->find($id);
        $this->showModal = true;
    }

    public function approve(): void
    {
        if ($this->selectedTestimonial) {
            $this->selectedTestimonial->update([
                'is_approved' => true,
            ]);

            $this->showModal = false;
            $this->selectedTestimonial = null;
        }
    }

    public function reject(): void
    {
        if ($this->selectedTestimonial) {
            $this->selectedTestimonial->update([
                'is_public' => false,
                'is_approved' => false,
            ]);

            $this->showModal = false;
            $this->selectedTestimonial = null;
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedTestimonial = null;
    }

    public function render(): View
    {
        return view('livewire.admin.testimonial-list', [
            'testimonials' => $this->testimonialsQuery()->paginate(10),
        ]);
    }

    private function testimonialsQuery(): Builder
    {
        $query = Testimonial::query()->with('prayerRequest');

        if ($this->filter === 'pending') {
            $query->pending();
        } elseif ($this->filter === 'approved') {
            $query->approved();
        } elseif ($this->filter === 'private') {
            $query->where('is_public', false);
        }

        return $query->orderByDesc('created_at');
    }
}
