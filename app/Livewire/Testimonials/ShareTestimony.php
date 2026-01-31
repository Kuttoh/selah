<?php

namespace App\Livewire\Testimonials;

use App\Models\Testimonial;
use Illuminate\View\View;
use Livewire\Component;

class ShareTestimony extends Component
{
    public string $content = '';

    public string $displayName = '';

    public bool $isPublic = true;

    public bool $submitted = false;

    /**
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:2000'],
            'displayName' => ['nullable', 'string', 'max:255'],
            'isPublic' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'content.required' => 'Please share your testimony.',
            'content.max' => 'Your testimony must be less than 2000 characters.',
        ];
    }

    public function submit(): void
    {
        $this->validate();

        Testimonial::create([
            'content' => $this->content,
            'display_name' => $this->displayName ?: null,
            'is_public' => $this->isPublic,
            'is_approved' => false,
            'prayer_request_id' => null,
        ]);

        $this->submitted = true;
        $this->reset(['content', 'displayName', 'isPublic']);
    }

    public function render(): View
    {
        return view('testimonials.share-testimony');
    }
}
