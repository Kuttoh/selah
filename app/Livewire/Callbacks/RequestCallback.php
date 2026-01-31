<?php

namespace App\Livewire\Callbacks;

use App\Models\Callback;
use App\Models\PrayerRequest;
use App\Models\Service;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;

class RequestCallback extends Component
{
    public string $name = '';

    public string $phone = '';

    public ?int $serviceId = null;

    public bool $submitted = false;

    public ?int $prayerRequestId = null;

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

    public function mount(): void
    {
        $prayerToken = request()->query('prayer');

        if ($prayerToken) {
            $prayerRequest = PrayerRequest::where('public_token', $prayerToken)->first();

            if ($prayerRequest) {
                $this->prayerRequestId = $prayerRequest->id;
                $this->name = $prayerRequest->name ?? '';
            }
        }
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
        $this->reset(['name', 'phone', 'serviceId']);
    }

    public function render(): View
    {
        return view('callbacks.request-callback', [
            'services' => Service::query()->active()->orderBy('name')->get(),
        ]);
    }
}
