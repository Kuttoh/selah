<?php

namespace Tests\Feature;

use App\Livewire\Testimonials\SubmitTestimonial;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SubmitTestimonialTest extends TestCase
{
    use RefreshDatabase;

    public function test_modal_initially_closed(): void
    {
        Livewire::test(SubmitTestimonial::class)
            ->assertSet('showModal', false)
            ->assertSet('submitted', false);
    }

    public function test_open_modal(): void
    {
        Livewire::test(SubmitTestimonial::class)
            ->call('openModal')
            ->assertSet('showModal', true);
    }

    public function test_close_modal_resets_form(): void
    {
        Livewire::test(SubmitTestimonial::class)
            ->call('openModal')
            ->set('content', 'Test testimonial content')
            ->set('displayName', 'Test User')
            ->call('closeModal')
            ->assertSet('showModal', false)
            ->assertSet('content', '')
            ->assertSet('displayName', '');
    }

    public function test_submit_requires_content(): void
    {
        Livewire::test(SubmitTestimonial::class)
            ->call('submit')
            ->assertHasErrors(['content']);
    }

    public function test_submit_content_max_length(): void
    {
        $longContent = str_repeat('a', 2001);

        Livewire::test(SubmitTestimonial::class)
            ->set('content', $longContent)
            ->call('submit')
            ->assertHasErrors(['content']);
    }

    public function test_submit_display_name_max_length(): void
    {
        $longName = str_repeat('a', 256);

        Livewire::test(SubmitTestimonial::class)
            ->set('content', 'Valid testimonial content')
            ->set('displayName', $longName)
            ->call('submit')
            ->assertHasErrors(['displayName']);
    }

    public function test_can_submit_testimonial_with_display_name(): void
    {
        Livewire::test(SubmitTestimonial::class)
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
        Livewire::test(SubmitTestimonial::class)
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
        Livewire::test(SubmitTestimonial::class)
            ->set('content', 'Test content')
            ->set('displayName', 'Test Name')
            ->call('submit')
            ->assertSet('content', '')
            ->assertSet('displayName', '')
            ->assertSet('submitted', true);
    }

    public function test_modal_closes_after_submission(): void
    {
        Livewire::test(SubmitTestimonial::class)
            ->call('openModal')
            ->assertSet('showModal', true)
            ->set('content', 'Test testimonial')
            ->call('submit')
            ->assertSet('showModal', false);
    }

    public function test_submitted_state_shows_success_message(): void
    {
        Livewire::test(SubmitTestimonial::class)
            ->set('content', 'Test testimonial')
            ->call('submit')
            ->assertSee('Thank you for sharing your testimony!');
    }

    public function test_button_replaced_with_success_text_after_submission(): void
    {
        Livewire::test(SubmitTestimonial::class)
            ->assertSeeHtml('Share your testimony')
            ->set('content', 'Test testimonial')
            ->call('submit')
            ->assertDontSeeHtml('Share your testimony')
            ->assertSee('Thank you for sharing your testimony!');
    }
}
