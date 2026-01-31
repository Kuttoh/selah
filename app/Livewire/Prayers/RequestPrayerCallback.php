<?php

namespace App\Livewire\Prayers;

use App\Models\Callback;
use App\Models\PrayerRequest;
use App\Models\Service;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class RequestPrayerCallback extends Component
{
    #[Locked]
    public string $publicToken = '';

    public string $name = '';

    public string $phone = '';

    public ?int $serviceId = null;

    public bool $showModal = false;

    public bool $submitted = false;

    #[Locked]
    public ?int $prayerRequestId = null;

    #[Locked]
    public bool $hasExistingCallback = false;

    /**
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^(?:\+254|0)[17]\d{8}$/'],
            'serviceId' => ['nullable', 'exists:services,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'phone.regex' => 'Please enter a valid Kenyan phone number (e.g., 0712345678 or +254712345678).',
        ];
    }

    public function mount(string $publicToken): void
    {
        $this->publicToken = $publicToken;

        $prayerRequest = PrayerRequest::where('public_token', $publicToken)->first();

        if ($prayerRequest) {
            $this->prayerRequestId = $prayerRequest->id;
            $this->name = $prayerRequest->name ?? '';

            // Check if a callback already exists for this prayer
            $this->hasExistingCallback = Callback::where('prayer_request_id', $prayerRequest->id)->exists();
        }
    }

    public function openModal(): void
    {
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetValidation();
    }

    public function submit(): void
    {
        $this->validate();

        Callback::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'service_id' => $this->serviceId,
            'prayer_request_id' => $this->prayerRequestId,
            'public_token' => Str::uuid()->toString(),
        ]);

        $this->submitted = true;
        $this->showModal = false;
        $this->reset(['name', 'phone', 'serviceId']);
    }

    public function render(): View
    {
        return view('prayers.request-prayer-callback', [
            'services' => Service::query()->active()->orderBy('name')->get(),
        ]);
    }
}
