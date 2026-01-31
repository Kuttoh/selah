<?php

namespace Tests\Feature\Prayers;

use App\Livewire\Prayers\RequestPrayerCallback;
use App\Models\Callback;
use App\Models\PrayerRequest;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RequestPrayerCallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_component_renders_on_progress_page(): void
    {
        $prayer = PrayerRequest::factory()->create();

        $response = $this->get(route('prayers.progress', $prayer->public_token));

        $response->assertStatus(200);
        $response->assertSeeLivewire(RequestPrayerCallback::class);
    }

    public function test_component_initially_shows_request_button(): void
    {
        $prayer = PrayerRequest::factory()->create();

        Livewire::test(RequestPrayerCallback::class, ['publicToken' => $prayer->public_token])
            ->assertSet('submitted', false)
            ->assertSet('showModal', false)
            ->assertSet('hasExistingCallback', false)
            ->assertSee('Request Callback');
    }

    public function test_component_prefills_name_from_prayer(): void
    {
        $prayer = PrayerRequest::factory()->create(['name' => 'John Doe']);

        Livewire::test(RequestPrayerCallback::class, ['publicToken' => $prayer->public_token])
            ->assertSet('name', 'John Doe')
            ->assertSet('prayerRequestId', $prayer->id);
    }

    public function test_open_modal(): void
    {
        $prayer = PrayerRequest::factory()->create();

        Livewire::test(RequestPrayerCallback::class, ['publicToken' => $prayer->public_token])
            ->call('openModal')
            ->assertSet('showModal', true);
    }

    public function test_close_modal(): void
    {
        $prayer = PrayerRequest::factory()->create();

        Livewire::test(RequestPrayerCallback::class, ['publicToken' => $prayer->public_token])
            ->call('openModal')
            ->assertSet('showModal', true)
            ->call('closeModal')
            ->assertSet('showModal', false);
    }

    public function test_submit_requires_name(): void
    {
        $prayer = PrayerRequest::factory()->create(['name' => null]);

        Livewire::test(RequestPrayerCallback::class, ['publicToken' => $prayer->public_token])
            ->set('phone', '0712345678')
            ->call('submit')
            ->assertHasErrors(['name']);
    }

    public function test_submit_requires_phone(): void
    {
        $prayer = PrayerRequest::factory()->create();

        Livewire::test(RequestPrayerCallback::class, ['publicToken' => $prayer->public_token])
            ->set('name', 'John Doe')
            ->call('submit')
            ->assertHasErrors(['phone']);
    }

    public function test_submit_validates_phone_format(): void
    {
        $prayer = PrayerRequest::factory()->create();

        Livewire::test(RequestPrayerCallback::class, ['publicToken' => $prayer->public_token])
            ->set('name', 'John Doe')
            ->set('phone', 'invalid-phone')
            ->call('submit')
            ->assertHasErrors(['phone']);
    }

    public function test_can_submit_callback_request(): void
    {
        $prayer = PrayerRequest::factory()->create();

        Livewire::test(RequestPrayerCallback::class, ['publicToken' => $prayer->public_token])
            ->set('name', 'Jane Doe')
            ->set('phone', '0712345678')
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('showModal', false);

        $callback = Callback::first();
        $this->assertNotNull($callback);
        $this->assertSame('Jane Doe', $callback->name);
        $this->assertSame('0712345678', $callback->phone);
        $this->assertSame($prayer->id, $callback->prayer_request_id);
    }

    public function test_can_submit_with_service(): void
    {
        $prayer = PrayerRequest::factory()->create();
        $service = Service::factory()->create(['active' => true]);

        Livewire::test(RequestPrayerCallback::class, ['publicToken' => $prayer->public_token])
            ->set('name', 'Jane Doe')
            ->set('phone', '+254712345678')
            ->set('serviceId', $service->id)
            ->call('submit')
            ->assertSet('submitted', true);

        $callback = Callback::first();
        $this->assertSame($service->id, $callback->service_id);
    }

    public function test_submitted_state_shows_success_message(): void
    {
        $prayer = PrayerRequest::factory()->create();

        Livewire::test(RequestPrayerCallback::class, ['publicToken' => $prayer->public_token])
            ->set('name', 'Jane Doe')
            ->set('phone', '0712345678')
            ->call('submit')
            ->assertSee('Callback Requested!')
            ->assertSee('Thank you! We will contact you soon.');
    }

    public function test_shows_existing_callback_status_when_already_requested(): void
    {
        $prayer = PrayerRequest::factory()->create();
        Callback::factory()->create(['prayer_request_id' => $prayer->id]);

        Livewire::test(RequestPrayerCallback::class, ['publicToken' => $prayer->public_token])
            ->assertSet('hasExistingCallback', true)
            ->assertSee('Callback Requested')
            ->assertSee('We have your callback request');
    }

    public function test_form_resets_after_submission(): void
    {
        $prayer = PrayerRequest::factory()->create(['name' => 'Original Name']);

        Livewire::test(RequestPrayerCallback::class, ['publicToken' => $prayer->public_token])
            ->set('name', 'Updated Name')
            ->set('phone', '0712345678')
            ->call('submit')
            ->assertSet('name', '')
            ->assertSet('phone', '')
            ->assertSet('serviceId', null);
    }
}
