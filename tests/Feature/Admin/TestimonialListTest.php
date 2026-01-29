<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\TestimonialList;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;
use Tests\TestCase;

class TestimonialListTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_testimonials_route_requires_authentication(): void
    {
        $response = $this->get('/admin/testimonials');

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_testimonials_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/testimonials');

        $response->assertStatus(200);
    }

    public function test_shows_all_testimonials_by_default(): void
    {
        Testimonial::factory()->pending()->create();
        Testimonial::factory()->approved()->create();
        Testimonial::factory()->create(['is_public' => false]);

        Livewire::test(TestimonialList::class)
            ->assertSet('filter', 'all')
            ->assertViewHas('testimonials', function (LengthAwarePaginator $testimonials) {
                return $testimonials->total() === 3;
            });
    }

    public function test_filters_pending_testimonials(): void
    {
        Testimonial::factory()->pending()->create();
        Testimonial::factory()->approved()->create();
        Testimonial::factory()->create(['is_public' => false]);

        Livewire::test(TestimonialList::class)
            ->call('setFilter', 'pending')
            ->assertSet('filter', 'pending')
            ->assertViewHas('testimonials', function (LengthAwarePaginator $testimonials) {
                return $testimonials->total() === 1;
            });
    }

    public function test_filters_approved_testimonials(): void
    {
        Testimonial::factory()->pending()->create();
        Testimonial::factory()->approved()->create();

        Livewire::test(TestimonialList::class)
            ->call('setFilter', 'approved')
            ->assertSet('filter', 'approved')
            ->assertViewHas('testimonials', function (LengthAwarePaginator $testimonials) {
                return $testimonials->total() === 1 && $testimonials->first()->is_approved === true;
            });
    }

    public function test_filters_all_testimonials(): void
    {
        Testimonial::factory()->pending()->create();
        Testimonial::factory()->approved()->create();
        Testimonial::factory()->create(['is_public' => false]);

        Livewire::test(TestimonialList::class)
            ->call('setFilter', 'all')
            ->assertSet('filter', 'all')
            ->assertViewHas('testimonials', function (LengthAwarePaginator $testimonials) {
                return $testimonials->total() === 3;
            });
    }

    public function test_can_approve_pending_testimonial(): void
    {
        $testimonial = Testimonial::factory()->pending()->create();

        Livewire::test(TestimonialList::class)
            ->call('show', $testimonial->id)
            ->assertSet('showModal', true)
            ->call('approve')
            ->assertSet('showModal', false)
            ->assertSet('selectedTestimonial', null);

        $testimonial->refresh();
        $this->assertTrue($testimonial->is_approved);
    }

    public function test_can_reject_pending_testimonial(): void
    {
        $testimonial = Testimonial::factory()->pending()->create();

        Livewire::test(TestimonialList::class)
            ->call('show', $testimonial->id)
            ->call('reject')
            ->assertSet('showModal', false);

        $testimonial->refresh();
        $this->assertFalse($testimonial->is_public);
        $this->assertFalse($testimonial->is_approved);
    }

    public function test_close_modal_clears_selected_testimonial(): void
    {
        $testimonial = Testimonial::factory()->pending()->create();

        Livewire::test(TestimonialList::class)
            ->call('show', $testimonial->id)
            ->assertSet('showModal', true)
            ->call('closeModal')
            ->assertSet('showModal', false)
            ->assertSet('selectedTestimonial', null);
    }

    public function test_can_create_testimonial_without_prayer_request(): void
    {
        Livewire::test(TestimonialList::class)
            ->call('openCreateModal')
            ->assertSet('showCreateModal', true)
            ->set('newContent', 'A wonderful testimony from someone!')
            ->set('newDisplayName', 'John Doe')
            ->set('newIsPublic', true)
            ->set('newIsApproved', true)
            ->call('createTestimonial')
            ->assertSet('showCreateModal', false);

        $this->assertDatabaseHas('testimonials', [
            'content' => 'A wonderful testimony from someone!',
            'display_name' => 'John Doe',
            'is_public' => true,
            'is_approved' => true,
            'prayer_request_id' => null,
        ]);
    }

    public function test_create_testimonial_requires_content(): void
    {
        Livewire::test(TestimonialList::class)
            ->call('openCreateModal')
            ->set('newContent', '')
            ->call('createTestimonial')
            ->assertHasErrors(['newContent' => 'required']);
    }

    public function test_close_create_modal_resets_form(): void
    {
        Livewire::test(TestimonialList::class)
            ->call('openCreateModal')
            ->set('newContent', 'Some content')
            ->set('newDisplayName', 'Test Name')
            ->set('newIsPublic', false)
            ->call('closeCreateModal')
            ->assertSet('showCreateModal', false)
            ->assertSet('newContent', '')
            ->assertSet('newDisplayName', '')
            ->assertSet('newIsPublic', true)
            ->assertSet('newIsApproved', true);
    }
}
