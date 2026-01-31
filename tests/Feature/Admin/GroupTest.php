<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\GroupList;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;
use Tests\TestCase;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_groups_route_requires_authentication(): void
    {
        $response = $this->get('/admin/groups');

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_groups_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/groups');

        $response->assertStatus(200);
    }

    public function test_shows_all_groups_by_default(): void
    {
        Group::factory()->active()->create();
        Group::factory()->inactive()->create();

        Livewire::test(GroupList::class)
            ->assertSet('filter', 'all')
            ->assertViewHas('groups', function (LengthAwarePaginator $groups) {
                return $groups->total() === 2;
            });
    }

    public function test_filters_active_groups(): void
    {
        Group::factory()->active()->create();
        Group::factory()->inactive()->create();

        Livewire::test(GroupList::class)
            ->set('filter', 'active')
            ->assertSet('filter', 'active')
            ->assertViewHas('groups', function (LengthAwarePaginator $groups) {
                return $groups->total() === 1 && $groups->first()->active === true;
            });
    }

    public function test_filters_inactive_groups(): void
    {
        Group::factory()->active()->create();
        Group::factory()->inactive()->create();

        Livewire::test(GroupList::class)
            ->set('filter', 'inactive')
            ->assertSet('filter', 'inactive')
            ->assertViewHas('groups', function (LengthAwarePaginator $groups) {
                return $groups->total() === 1 && $groups->first()->active === false;
            });
    }

    public function test_groups_are_ordered_by_display_order_then_name(): void
    {
        Group::factory()->create(['name' => 'Youth', 'display_order' => 2]);
        Group::factory()->create(['name' => 'Men', 'display_order' => 1]);
        Group::factory()->create(['name' => 'Women', 'display_order' => 1]);

        Livewire::test(GroupList::class)
            ->assertViewHas('groups', function (LengthAwarePaginator $groups) {
                $items = $groups->items();

                return $items[0]->name === 'Men' &&
                    $items[1]->name === 'Women' &&
                    $items[2]->name === 'Youth';
            });
    }

    public function test_can_create_group(): void
    {
        Livewire::test(GroupList::class)
            ->call('openCreateModal')
            ->assertSet('showModal', true)
            ->assertSet('isEditing', false)
            ->set('name', 'Men')
            ->set('description', 'Men\'s fellowship group')
            ->set('active', true)
            ->set('displayOrder', 1)
            ->call('save')
            ->assertSet('showModal', false);

        $this->assertDatabaseHas('groups', [
            'name' => 'Men',
            'description' => 'Men\'s fellowship group',
            'active' => true,
            'display_order' => 1,
        ]);
    }

    public function test_display_order_auto_increments_on_create(): void
    {
        Group::factory()->create(['display_order' => 5]);
        Group::factory()->create(['display_order' => 3]);

        Livewire::test(GroupList::class)
            ->call('openCreateModal')
            ->assertSet('displayOrder', 6);
    }

    public function test_can_edit_group(): void
    {
        $group = Group::factory()->create([
            'name' => 'Old Name',
            'description' => 'Old description',
            'active' => true,
            'display_order' => 1,
        ]);

        Livewire::test(GroupList::class)
            ->call('openEditModal', $group->id)
            ->assertSet('showModal', true)
            ->assertSet('isEditing', true)
            ->assertSet('name', 'Old Name')
            ->assertSet('description', 'Old description')
            ->set('name', 'New Name')
            ->set('description', 'New description')
            ->set('displayOrder', 2)
            ->call('save')
            ->assertSet('showModal', false);

        $group->refresh();
        $this->assertEquals('New Name', $group->name);
        $this->assertEquals('New description', $group->description);
        $this->assertEquals(2, $group->display_order);
    }

    public function test_can_delete_group(): void
    {
        $group = Group::factory()->create();

        Livewire::test(GroupList::class)
            ->call('delete', $group->id);

        $this->assertSoftDeleted('groups', ['id' => $group->id]);
    }

    public function test_can_toggle_active_status(): void
    {
        $group = Group::factory()->active()->create();

        Livewire::test(GroupList::class)
            ->call('toggleActive', $group->id);

        $group->refresh();
        $this->assertFalse($group->active);

        Livewire::test(GroupList::class)
            ->call('toggleActive', $group->id);

        $group->refresh();
        $this->assertTrue($group->active);
    }

    public function test_create_group_requires_name(): void
    {
        Livewire::test(GroupList::class)
            ->call('openCreateModal')
            ->set('name', '')
            ->call('save')
            ->assertHasErrors(['name' => 'required']);
    }

    public function test_close_modal_resets_form(): void
    {
        Livewire::test(GroupList::class)
            ->call('openCreateModal')
            ->set('name', 'Test Group')
            ->set('description', 'Test description')
            ->call('closeModal')
            ->assertSet('showModal', false)
            ->assertSet('name', '')
            ->assertSet('description', '')
            ->assertSet('isEditing', false)
            ->assertSet('selectedGroup', null);
    }

    public function test_scope_active_filters_only_active_groups(): void
    {
        Group::factory()->active()->create(['name' => 'Active Group']);
        Group::factory()->inactive()->create(['name' => 'Inactive Group']);

        $activeGroups = Group::active()->get();

        $this->assertCount(1, $activeGroups);
        $this->assertEquals('Active Group', $activeGroups->first()->name);
    }

    public function test_groups_pagination_works(): void
    {
        Group::factory()->count(15)->create();

        Livewire::test(GroupList::class)
            ->assertViewHas('groups', function (LengthAwarePaginator $groups) {
                return $groups->total() === 15 && $groups->perPage() === 10;
            });
    }
}
