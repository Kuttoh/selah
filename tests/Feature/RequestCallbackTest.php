<?php

namespace Tests\Feature;

use App\Livewire\Callbacks\RequestCallback;
use App\Models\Callback;
use App\Models\PrayerRequest;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RequestCallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_callback_request_page_is_accessible(): void
    {
        $response = $this->get('/callbacks/request');

        $response->assertStatus(200);
    }

    public function test_callback_request_page_shows_livewire_component(): void
    {
        $response = $this->get('/callbacks/request');

        $response->assertSeeLivewire(RequestCallback::class);
    }

    public function test_name_is_required(): void
    {
        Livewire::test(RequestCallback::class)
            ->set('phone', '0712345678')
            ->call('submit')
            ->assertHasErrors(['name']);
    }

    public function test_phone_is_required(): void
    {
        Livewire::test(RequestCallback::class)
            ->set('name', 'John Doe')
            ->call('submit')
            ->assertHasErrors(['phone']);
    }

    public function test_phone_must_be_valid_kenyan_number(): void
    {
        Livewire::test(RequestCallback::class)
            ->set('name', 'John Doe')
            ->set('phone', '12345')
            ->call('submit')
            ->assertHasErrors(['phone']);

        Livewire::test(RequestCallback::class)
            ->set('name', 'John Doe')
            ->set('phone', '0712345678')
            ->call('submit')
            ->assertHasNoErrors(['phone']);

        Livewire::test(RequestCallback::class)
            ->set('name', 'John Doe')
            ->set('phone', '+254712345678')
            ->call('submit')
            ->assertHasNoErrors(['phone']);
    }

    public function test_can_submit_callback_request(): void
    {
        Livewire::test(RequestCallback::class)
            ->set('name', 'John Doe')
            ->set('phone', '0712345678')
            ->call('submit')
            ->assertSet('submitted', true);

        $this->assertDatabaseHas('callbacks', [
            'name' => 'John Doe',
            'phone' => '0712345678',
        ]);
    }

    public function test_callback_gets_public_token_on_creation(): void
    {
        Livewire::test(RequestCallback::class)
            ->set('name', 'John Doe')
            ->set('phone', '0712345678')
            ->call('submit');

        $callback = Callback::first();
        $this->assertNotNull($callback->public_token);
    }

    public function test_can_submit_callback_with_optional_service(): void
    {
        $service = Service::factory()->active()->create();

        Livewire::test(RequestCallback::class)
            ->set('name', 'John Doe')
            ->set('phone', '0712345678')
            ->set('serviceId', $service->id)
            ->call('submit')
            ->assertSet('submitted', true);

        $this->assertDatabaseHas('callbacks', [
            'name' => 'John Doe',
            'phone' => '0712345678',
            'service_id' => $service->id,
        ]);
    }

    public function test_callback_linked_to_prayer_request_via_query_param(): void
    {
        $prayerRequest = PrayerRequest::factory()->create([
            'name' => 'Prayer Person',
            'public_token' => 'test-prayer-token',
        ]);

        Livewire::withQueryParams(['prayer' => 'test-prayer-token'])
            ->test(RequestCallback::class)
            ->assertSet('name', 'Prayer Person')
            ->assertSet('prayerRequestId', $prayerRequest->id);
    }

    public function test_callback_created_with_prayer_request_link(): void
    {
        $prayerRequest = PrayerRequest::factory()->create([
            'name' => 'Prayer Person',
            'public_token' => 'test-prayer-token',
        ]);

        Livewire::withQueryParams(['prayer' => 'test-prayer-token'])
            ->test(RequestCallback::class)
            ->set('phone', '0712345678')
            ->call('submit')
            ->assertSet('submitted', true);

        $this->assertDatabaseHas('callbacks', [
            'name' => 'Prayer Person',
            'phone' => '0712345678',
            'prayer_request_id' => $prayerRequest->id,
        ]);
    }

    public function test_only_active_services_shown_in_dropdown(): void
    {
        $activeService = Service::factory()->active()->create(['name' => 'Active Service']);
        $inactiveService = Service::factory()->inactive()->create(['name' => 'Inactive Service']);

        Livewire::test(RequestCallback::class)
            ->assertViewHas('services', function ($services) use ($activeService, $inactiveService) {
                return $services->contains($activeService) && ! $services->contains($inactiveService);
            });
    }

    public function test_form_resets_after_submission(): void
    {
        Livewire::test(RequestCallback::class)
            ->set('name', 'John Doe')
            ->set('phone', '0712345678')
            ->call('submit')
            ->assertSet('name', '')
            ->assertSet('phone', '');
    }
}
