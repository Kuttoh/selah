<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\ServiceList;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_services_route_requires_authentication(): void
    {
        $response = $this->get('/admin/services');

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_services_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/services');

        $response->assertStatus(200);
    }

    public function test_shows_all_services_by_default(): void
    {
        Service::factory()->active()->create();
        Service::factory()->inactive()->create();

        Livewire::test(ServiceList::class)
            ->assertSet('filter', 'all')
            ->assertViewHas('services', function (LengthAwarePaginator $services) {
                return $services->total() === 2;
            });
    }

    public function test_filters_active_services(): void
    {
        Service::factory()->active()->create();
        Service::factory()->inactive()->create();

        Livewire::test(ServiceList::class)
            ->set('filter', 'active')
            ->assertSet('filter', 'active')
            ->assertViewHas('services', function (LengthAwarePaginator $services) {
                return $services->total() === 1 && $services->first()->active === true;
            });
    }

    public function test_filters_inactive_services(): void
    {
        Service::factory()->active()->create();
        Service::factory()->inactive()->create();

        Livewire::test(ServiceList::class)
            ->set('filter', 'inactive')
            ->assertSet('filter', 'inactive')
            ->assertViewHas('services', function (LengthAwarePaginator $services) {
                return $services->total() === 1 && $services->first()->active === false;
            });
    }

    public function test_services_are_ordered_by_display_order_then_name(): void
    {
        Service::factory()->create(['name' => 'Youth 9.30am', 'display_order' => 2]);
        Service::factory()->create(['name' => 'Main 8am', 'display_order' => 1]);
        Service::factory()->create(['name' => 'Vuka 11.30am', 'display_order' => 1]);

        Livewire::test(ServiceList::class)
            ->assertViewHas('services', function (LengthAwarePaginator $services) {
                $items = $services->items();

                return $items[0]->name === 'Main 8am' &&
                    $items[1]->name === 'Vuka 11.30am' &&
                    $items[2]->name === 'Youth 9.30am';
            });
    }

    public function test_can_create_service(): void
    {
        Livewire::test(ServiceList::class)
            ->call('openCreateModal')
            ->assertSet('showModal', true)
            ->assertSet('isEditing', false)
            ->set('name', 'Main 8am')
            ->set('description', 'Main morning service at 8:00 AM')
            ->set('active', true)
            ->set('displayOrder', 1)
            ->call('save')
            ->assertSet('showModal', false);

        $this->assertDatabaseHas('services', [
            'name' => 'Main 8am',
            'description' => 'Main morning service at 8:00 AM',
            'active' => true,
            'display_order' => 1,
        ]);
    }

    public function test_display_order_auto_increments_on_create(): void
    {
        Service::factory()->create(['display_order' => 5]);
        Service::factory()->create(['display_order' => 3]);

        Livewire::test(ServiceList::class)
            ->call('openCreateModal')
            ->assertSet('displayOrder', 6);
    }

    public function test_can_edit_service(): void
    {
        $service = Service::factory()->create([
            'name' => 'Old Name',
            'description' => 'Old description',
            'active' => true,
            'display_order' => 1,
        ]);

        Livewire::test(ServiceList::class)
            ->call('openEditModal', $service->id)
            ->assertSet('showModal', true)
            ->assertSet('isEditing', true)
            ->assertSet('name', 'Old Name')
            ->assertSet('description', 'Old description')
            ->set('name', 'New Name')
            ->set('description', 'New description')
            ->set('displayOrder', 2)
            ->call('save')
            ->assertSet('showModal', false);

        $service->refresh();
        $this->assertEquals('New Name', $service->name);
        $this->assertEquals('New description', $service->description);
        $this->assertEquals(2, $service->display_order);
    }

    public function test_can_delete_service(): void
    {
        $service = Service::factory()->create();

        Livewire::test(ServiceList::class)
            ->call('delete', $service->id);

        $this->assertSoftDeleted('services', ['id' => $service->id]);
    }

    public function test_can_toggle_active_status(): void
    {
        $service = Service::factory()->active()->create();

        Livewire::test(ServiceList::class)
            ->call('toggleActive', $service->id);

        $service->refresh();
        $this->assertFalse($service->active);

        Livewire::test(ServiceList::class)
            ->call('toggleActive', $service->id);

        $service->refresh();
        $this->assertTrue($service->active);
    }

    public function test_create_service_requires_name(): void
    {
        Livewire::test(ServiceList::class)
            ->call('openCreateModal')
            ->set('name', '')
            ->call('save')
            ->assertHasErrors(['name' => 'required']);
    }

    public function test_close_modal_resets_form(): void
    {
        Livewire::test(ServiceList::class)
            ->call('openCreateModal')
            ->set('name', 'Test Service')
            ->set('description', 'Test description')
            ->call('closeModal')
            ->assertSet('showModal', false)
            ->assertSet('name', '')
            ->assertSet('description', '')
            ->assertSet('isEditing', false)
            ->assertSet('selectedService', null);
    }

    public function test_scope_active_filters_only_active_services(): void
    {
        Service::factory()->active()->create(['name' => 'Active Service']);
        Service::factory()->inactive()->create(['name' => 'Inactive Service']);

        $activeServices = Service::active()->get();

        $this->assertCount(1, $activeServices);
        $this->assertEquals('Active Service', $activeServices->first()->name);
    }

    public function test_services_pagination_works(): void
    {
        Service::factory()->count(15)->create();

        Livewire::test(ServiceList::class)
            ->assertViewHas('services', function (LengthAwarePaginator $services) {
                return $services->total() === 15 && $services->perPage() === 10;
            });
    }
}
