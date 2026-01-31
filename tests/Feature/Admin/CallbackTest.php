<?php

namespace Tests\Feature\Admin;

use App\Enums\CallbackStatus;
use App\Livewire\Admin\CallbackDetail;
use App\Livewire\Admin\CallbackList;
use App\Models\Callback;
use App\Models\CallbackInteraction;
use App\Models\PrayerRequest;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;
use Tests\TestCase;

class CallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_callbacks_route_requires_authentication(): void
    {
        $response = $this->get('/admin/callbacks');

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_callbacks_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/callbacks');

        $response->assertStatus(200);
    }

    public function test_shows_all_callbacks_by_default(): void
    {
        Callback::factory()->count(3)->create();

        Livewire::test(CallbackList::class)
            ->assertSet('filter', 'all')
            ->assertViewHas('callbacks', function (LengthAwarePaginator $callbacks) {
                return $callbacks->total() === 3;
            });
    }

    public function test_can_filter_by_pending_status(): void
    {
        // Create a callback with no interactions (pending)
        Callback::factory()->create();

        // Create a callback with a "called" interaction
        $calledCallback = Callback::factory()->create();
        CallbackInteraction::factory()->called()->create([
            'callback_id' => $calledCallback->id,
        ]);

        Livewire::test(CallbackList::class)
            ->set('filter', 'pending')
            ->assertViewHas('callbacks', function (LengthAwarePaginator $callbacks) {
                return $callbacks->total() === 1;
            });
    }

    public function test_can_filter_by_called_status(): void
    {
        // Create a callback with no interactions (pending)
        Callback::factory()->create();

        // Create a callback with a "called" interaction
        $calledCallback = Callback::factory()->create();
        CallbackInteraction::factory()->called()->create([
            'callback_id' => $calledCallback->id,
        ]);

        Livewire::test(CallbackList::class)
            ->set('filter', 'called')
            ->assertViewHas('callbacks', function (LengthAwarePaginator $callbacks) {
                return $callbacks->total() === 1;
            });
    }

    public function test_can_search_callbacks_by_name(): void
    {
        Callback::factory()->create(['name' => 'John Doe']);
        Callback::factory()->create(['name' => 'Jane Smith']);

        Livewire::test(CallbackList::class)
            ->set('search', 'John')
            ->assertViewHas('callbacks', function (LengthAwarePaginator $callbacks) {
                return $callbacks->total() === 1 && $callbacks->first()->name === 'John Doe';
            });
    }

    public function test_can_search_callbacks_by_phone(): void
    {
        Callback::factory()->create(['phone' => '0712345678']);
        Callback::factory()->create(['phone' => '0787654321']);

        Livewire::test(CallbackList::class)
            ->set('search', '0712')
            ->assertViewHas('callbacks', function (LengthAwarePaginator $callbacks) {
                return $callbacks->total() === 1 && $callbacks->first()->phone === '0712345678';
            });
    }

    public function test_can_create_callback(): void
    {
        Livewire::test(CallbackList::class)
            ->call('openCreateModal')
            ->assertSet('showModal', true)
            ->set('name', 'New Callback')
            ->set('phone', '0712345678')
            ->call('save')
            ->assertSet('showModal', false);

        $this->assertDatabaseHas('callbacks', [
            'name' => 'New Callback',
            'phone' => '0712345678',
        ]);
    }

    public function test_can_create_callback_with_service(): void
    {
        $service = Service::factory()->create();

        Livewire::test(CallbackList::class)
            ->call('openCreateModal')
            ->set('name', 'New Callback')
            ->set('phone', '0712345678')
            ->set('serviceId', $service->id)
            ->call('save');

        $this->assertDatabaseHas('callbacks', [
            'name' => 'New Callback',
            'service_id' => $service->id,
        ]);
    }

    public function test_can_delete_callback_from_list(): void
    {
        $callback = Callback::factory()->create();

        Livewire::test(CallbackList::class)
            ->call('confirmDelete', $callback->id)
            ->assertSet('showConfirmModal', true)
            ->assertSet('confirmCallbackId', $callback->id)
            ->call('delete');

        $this->assertSoftDeleted('callbacks', ['id' => $callback->id]);
    }

    public function test_can_add_interaction_from_list(): void
    {
        $user = User::factory()->create();
        $callback = Callback::factory()->create();

        Livewire::actingAs($user)
            ->test(CallbackList::class)
            ->call('openLogInteractionModal', $callback->id)
            ->assertSet('showLogModal', true)
            ->set('interactionStatus', 'called')
            ->set('interactionNotes', 'Called from list view')
            ->call('addInteraction')
            ->assertSet('showLogModal', false);

        $this->assertDatabaseHas('callback_interactions', [
            'callback_id' => $callback->id,
            'status' => 'called',
            'notes' => 'Called from list view',
            'created_by' => $user->id,
        ]);
    }

    public function test_callback_current_status_reflects_latest_interaction(): void
    {
        $callback = Callback::factory()->create();

        // Initially should be pending
        $this->assertEquals(CallbackStatus::Pending, $callback->current_status);

        // Add an interaction
        CallbackInteraction::factory()->called()->create([
            'callback_id' => $callback->id,
        ]);

        $callback->refresh();
        $this->assertEquals(CallbackStatus::Called, $callback->current_status);

        // Add another interaction
        CallbackInteraction::factory()->completed()->create([
            'callback_id' => $callback->id,
        ]);

        $callback->refresh();
        $this->assertEquals(CallbackStatus::Completed, $callback->current_status);
    }

    public function test_can_link_callback_to_prayer_request_on_create(): void
    {
        $prayerRequest = PrayerRequest::factory()->create(['name' => 'Prayer Person']);

        Livewire::test(CallbackList::class)
            ->call('openCreateModal')
            ->set('name', 'New Callback')
            ->set('phone', '0712345678')
            ->call('selectPrayerRequest', $prayerRequest->id)
            ->assertSet('prayerRequestId', $prayerRequest->id)
            ->call('save');

        $this->assertDatabaseHas('callbacks', [
            'name' => 'New Callback',
            'prayer_request_id' => $prayerRequest->id,
        ]);
    }

    public function test_can_clear_prayer_request_link(): void
    {
        $prayerRequest = PrayerRequest::factory()->create();

        Livewire::test(CallbackList::class)
            ->call('openCreateModal')
            ->call('selectPrayerRequest', $prayerRequest->id)
            ->assertSet('prayerRequestId', $prayerRequest->id)
            ->call('clearPrayerRequest')
            ->assertSet('prayerRequestId', null)
            ->assertSet('prayerRequestSearch', '');
    }

    // Detail Page Tests

    public function test_detail_page_requires_authentication(): void
    {
        $callback = Callback::factory()->create();

        $response = $this->get('/admin/callbacks/'.$callback->id);

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_detail_page(): void
    {
        $user = User::factory()->create();
        $callback = Callback::factory()->create();

        $response = $this->actingAs($user)->get('/admin/callbacks/'.$callback->id);

        $response->assertStatus(200);
        $response->assertSeeLivewire(CallbackDetail::class);
    }

    public function test_can_add_interaction_from_detail_page(): void
    {
        $user = User::factory()->create();
        $callback = Callback::factory()->create();

        Livewire::actingAs($user)
            ->test(CallbackDetail::class, ['callback' => $callback])
            ->call('openInteractionModal')
            ->assertSet('showInteractionModal', true)
            ->set('interactionStatus', 'called')
            ->set('interactionNotes', 'Spoke with the person')
            ->call('addInteraction')
            ->assertSet('showInteractionModal', false);

        $this->assertDatabaseHas('callback_interactions', [
            'callback_id' => $callback->id,
            'status' => 'called',
            'notes' => 'Spoke with the person',
            'created_by' => $user->id,
        ]);
    }

    public function test_interaction_status_is_required(): void
    {
        $callback = Callback::factory()->create();

        Livewire::test(CallbackDetail::class, ['callback' => $callback])
            ->call('openInteractionModal')
            ->set('interactionNotes', 'Some notes')
            ->call('addInteraction')
            ->assertHasErrors(['interactionStatus']);
    }

    public function test_detail_page_shows_interaction_history(): void
    {
        $user = User::factory()->create();
        $callback = Callback::factory()->create();
        CallbackInteraction::factory()->called()->create([
            'callback_id' => $callback->id,
            'notes' => 'First call',
            'created_by' => $user->id,
        ]);

        Livewire::test(CallbackDetail::class, ['callback' => $callback])
            ->assertViewHas('interactions', function ($interactions) {
                return $interactions->count() === 1 && $interactions->first()->notes === 'First call';
            });
    }
}
