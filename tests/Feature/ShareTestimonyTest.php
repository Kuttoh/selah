<?php

namespace Tests\Feature;

use App\Livewire\Testimonials\ShareTestimony;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ShareTestimonyTest extends TestCase
{
    use RefreshDatabase;

    public function test_share_testimony_page_is_accessible(): void
    {
        $response = $this->get(route('testimonials.share'));

        $response->assertStatus(200);
        $response->assertSeeLivewire(ShareTestimony::class);
    }

    public function test_component_initially_shows_form(): void
    {
        Livewire::test(ShareTestimony::class)
            ->assertSet('submitted', false)
            ->assertSet('content', '')
            ->assertSet('displayName', '');
    }

    public function test_submit_requires_content(): void
    {
        Livewire::test(ShareTestimony::class)
            ->call('submit')
            ->assertHasErrors(['content']);
    }

    public function test_submit_content_max_length(): void
    {
        $longContent = str_repeat('a', 2001);

        Livewire::test(ShareTestimony::class)
            ->set('content', $longContent)
            ->call('submit')
            ->assertHasErrors(['content']);
    }

    public function test_submit_display_name_max_length(): void
    {
        $longName = str_repeat('a', 256);

        Livewire::test(ShareTestimony::class)
            ->set('content', 'Valid testimonial content')
            ->set('displayName', $longName)
            ->call('submit')
            ->assertHasErrors(['displayName']);
    }

    public function test_can_submit_testimonial_with_display_name(): void
    {
        Livewire::test(ShareTestimony::class)
            ->set('content', 'God has blessed my life in many ways!')
            ->set('displayName', 'Sarah M.')
            ->call('submit')
            ->assertSet('submitted', true);

        $testimonial = Testimonial::first();
        $this->assertNotNull($testimonial);
        $this->assertSame('God has blessed my life in many ways!', $testimonial->content);
        $this->assertSame('Sarah M.', $testimonial->display_name);
        $this->assertTrue($testimonial->is_public);
        $this->assertFalse($testimonial->is_approved);
        $this->assertNull($testimonial->prayer_request_id);
    }

    public function test_can_submit_testimonial_anonymously(): void
    {
        Livewire::test(ShareTestimony::class)
            ->set('content', 'A humble testimony of faith')
            ->call('submit')
            ->assertSet('submitted', true);

        $testimonial = Testimonial::first();
        $this->assertNotNull($testimonial);
        $this->assertSame('A humble testimony of faith', $testimonial->content);
        $this->assertNull($testimonial->display_name);
        $this->assertTrue($testimonial->is_public);
        $this->assertFalse($testimonial->is_approved);
        $this->assertNull($testimonial->prayer_request_id);
    }

    public function test_form_resets_after_submission(): void
    {
        Livewire::test(ShareTestimony::class)
            ->set('content', 'Test content')
            ->set('displayName', 'Test Name')
            ->call('submit')
            ->assertSet('content', '')
            ->assertSet('displayName', '')
            ->assertSet('submitted', true);
    }

    public function test_success_state_shows_thank_you_message(): void
    {
        Livewire::test(ShareTestimony::class)
            ->set('content', 'Test testimonial')
            ->call('submit')
            ->assertSee('Thank You for Sharing');
    }

    public function test_success_state_shows_return_home_button(): void
    {
        Livewire::test(ShareTestimony::class)
            ->set('content', 'Test testimonial')
            ->call('submit')
            ->assertSee('Return Home');
    }

    public function test_homepage_has_share_testimony_link(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Share your testimony');
        $response->assertSee(route('testimonials.share'));
    }
}
