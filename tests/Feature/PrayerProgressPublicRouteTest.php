<?php

namespace Tests\Feature;

use App\Enums\PrayerStatus;
use App\Models\PrayerRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PrayerProgressPublicRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_not_prayed_state_renders_message(): void
    {
        $prayer = PrayerRequest::factory()->create([
            'public_token' => 'token-abc',
            'status' => PrayerStatus::Received,
            'prayer' => 'Please pray for my family.',
        ]);

        $response = $this->get('/prayers/token-abc');

        $response->assertStatus(200)
            ->assertSee('Prayer Progress')
            ->assertSee('Please pray for my family.')
            ->assertSee('Your prayer has not yet been marked as prayed.');
    }

    public function test_prayed_state_renders_message_with_date(): void
    {
        $date = Carbon::parse('2026-01-01 10:00:00');
        $prayer = PrayerRequest::factory()->create([
            'public_token' => 'token-def',
            'status' => PrayerStatus::Prayed,
            'prayed_at' => $date,
            'prayer' => 'Pray for health.',
        ]);

        $response = $this->get('/prayers/token-def');

        $response->assertStatus(200)
            ->assertSee('Pray for health.')
            ->assertSee('Your prayer was prayed for on '.$date->format('F j, Y g:i A'));
    }

    public function test_invalid_token_returns_404(): void
    {
        $response = $this->get('/prayers/does-not-exist');

        $response->assertStatus(404);
    }

    public function test_answered_state_renders_message_with_date(): void
    {
        $date = Carbon::parse('2026-01-15');
        $prayer = PrayerRequest::factory()->create([
            'public_token' => 'token-answered',
            'status' => PrayerStatus::Answered,
            'answered_at' => $date,
            'prayer' => 'God answered my prayer.',
        ]);

        $response = $this->get('/prayers/token-answered');

        $response->assertStatus(200)
            ->assertSee('God answered my prayer.')
            ->assertSee('Your prayer was answered on '.$date->format('F j, Y'));
    }

    public function test_mark_prayer_answered_component_is_present(): void
    {
        $prayer = PrayerRequest::factory()->create([
            'public_token' => 'token-component',
            'status' => PrayerStatus::Prayed,
        ]);

        $response = $this->get('/prayers/token-component');

        $response->assertStatus(200)
            ->assertSeeLivewire(\App\Livewire\Prayers\MarkPrayerAnswered::class);
    }
}
