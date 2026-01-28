<?php

namespace Tests\Feature;

use App\Livewire\TestimonialsCarousel;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TestimonialsCarouselTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_nothing_when_no_approved_testimonials(): void
    {
        Testimonial::factory()->pending()->create();
        Testimonial::factory()->create(['is_public' => false]);

        Livewire::test(TestimonialsCarousel::class)
            ->assertDontSee('Answered Prayers');
    }

    public function test_renders_carousel_when_approved_testimonials_exist(): void
    {
        Testimonial::factory()->approved()->create([
            'content' => 'God is good!',
            'display_name' => 'Sarah M.',
        ]);

        Livewire::test(TestimonialsCarousel::class)
            ->assertSee('Answered Prayers')
            ->assertSee('God is good!')
            ->assertSee('Sarah M.');
    }

    public function test_displays_anonymous_when_no_display_name(): void
    {
        Testimonial::factory()->approved()->create([
            'content' => 'A wonderful testimony',
            'display_name' => null,
        ]);

        Livewire::test(TestimonialsCarousel::class)
            ->assertSee('A wonderful testimony')
            ->assertSee('Anonymous');
    }

    public function test_only_shows_approved_testimonials(): void
    {
        Testimonial::factory()->approved()->create([
            'content' => 'Approved testimonial',
        ]);
        Testimonial::factory()->pending()->create([
            'content' => 'Pending testimonial',
        ]);
        Testimonial::factory()->create([
            'content' => 'Private testimonial',
            'is_public' => false,
        ]);

        Livewire::test(TestimonialsCarousel::class)
            ->assertSee('Approved testimonial')
            ->assertDontSee('Pending testimonial')
            ->assertDontSee('Private testimonial');
    }

    public function test_cta_page_shows_carousel_with_approved_testimonials(): void
    {
        Testimonial::factory()->approved()->create([
            'content' => 'My prayer was answered!',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee('Answered Prayers')
            ->assertSee('My prayer was answered!');
    }

    public function test_cta_page_hides_carousel_when_no_approved_testimonials(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertDontSee('Answered Prayers');
    }
}
