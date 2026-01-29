<?php

namespace Tests\Feature;

use App\Enums\PrayerStatus;
use App\Livewire\Prayers\MarkPrayerAnswered;
use App\Models\PrayerRequest;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MarkPrayerAnsweredTest extends TestCase
{
    use RefreshDatabase;

    public function test_component_mounts_with_prayer_by_public_token(): void
    {
        $prayer = PrayerRequest::factory()->create([
            'public_token' => 'test-token-123',
            'status' => PrayerStatus::Received,
        ]);

        Livewire::test(MarkPrayerAnswered::class, ['publicToken' => 'test-token-123'])
            ->assertSet('prayer.id', $prayer->id)
            ->assertSet('showModal', false)
            ->assertSet('submitted', false);
    }

    public function test_can_mark_prayer_as_answered_without_testimonial(): void
    {
        $prayer = PrayerRequest::factory()->create([
            'public_token' => 'test-token-456',
            'status' => PrayerStatus::Prayed,
        ]);

        Livewire::test(MarkPrayerAnswered::class, ['publicToken' => 'test-token-456'])
            ->call('openModal')
            ->assertSet('showModal', true)
            ->call('markAsAnswered')
            ->assertSet('submitted', true)
            ->assertSet('showModal', false);

        $prayer->refresh();
        $this->assertSame(PrayerStatus::Answered, $prayer->status);
        $this->assertNotNull($prayer->answered_at);
        $this->assertDatabaseCount('testimonials', 0);
    }

    public function test_can_mark_prayer_as_answered_with_testimonial(): void
    {
        $prayer = PrayerRequest::factory()->create([
            'public_token' => 'test-token-789',
            'status' => PrayerStatus::Prayed,
        ]);

        Livewire::test(MarkPrayerAnswered::class, ['publicToken' => 'test-token-789'])
            ->call('openModal')
            ->set('testimonialContent', 'God answered my prayer in a miraculous way!')
            ->set('displayName', 'John D.')
            ->set('isPublic', true)
            ->call('markAsAnswered')
            ->assertSet('submitted', true);

        $prayer->refresh();
        $this->assertSame(PrayerStatus::Answered, $prayer->status);
        $this->assertNotNull($prayer->answered_at);

        $testimonial = Testimonial::first();
        $this->assertNotNull($testimonial);
        $this->assertSame('God answered my prayer in a miraculous way!', $testimonial->content);
        $this->assertSame('John D.', $testimonial->display_name);
        $this->assertTrue($testimonial->is_public);
        $this->assertFalse($testimonial->is_approved);
        $this->assertSame($prayer->id, $testimonial->prayer_request_id);
    }

    public function test_testimonial_is_private_by_default(): void
    {
        $prayer = PrayerRequest::factory()->create([
            'public_token' => 'test-token-private',
            'status' => PrayerStatus::Prayed,
        ]);

        Livewire::test(MarkPrayerAnswered::class, ['publicToken' => 'test-token-private'])
            ->call('openModal')
            ->set('testimonialContent', 'A private testimonial')
            ->call('markAsAnswered');

        $testimonial = Testimonial::first();
        $this->assertFalse($testimonial->is_public);
        $this->assertFalse($testimonial->is_approved);
    }

    public function test_shows_already_answered_state(): void
    {
        $prayer = PrayerRequest::factory()->answered()->create([
            'public_token' => 'test-token-answered',
        ]);

        Livewire::test(MarkPrayerAnswered::class, ['publicToken' => 'test-token-answered'])
            ->assertSee('Prayer Answered!');
    }

    public function test_close_modal_resets_form_state(): void
    {
        $prayer = PrayerRequest::factory()->create([
            'public_token' => 'test-token-close',
            'status' => PrayerStatus::Prayed,
        ]);

        Livewire::test(MarkPrayerAnswered::class, ['publicToken' => 'test-token-close'])
            ->call('openModal')
            ->set('testimonialContent', 'Some content')
            ->set('displayName', 'Test Name')
            ->set('isPublic', true)
            ->call('closeModal')
            ->assertSet('showModal', false)
            ->assertSet('testimonialContent', '')
            ->assertSet('displayName', '')
            ->assertSet('isPublic', false);
    }
}
