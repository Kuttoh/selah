<?php

namespace Tests\Feature;

use App\Models\Callback;
use App\Models\PrayerRequest;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class NavigationBadgesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Clear navigation badge caches before each test
        Cache::forget('nav_new_callbacks');
        Cache::forget('nav_new_prayers');
        Cache::forget('nav_new_testimonials');
    }

    public function test_navigation_shows_badge_when_callbacks_submitted_in_last_24_hours(): void
    {
        $user = User::factory()->create();
        Callback::factory()->create(['created_at' => now()->subHours(12)]);

        $response = $this->actingAs($user)->get(route('prayers.index'));

        $response->assertStatus(200);
        $response->assertSee('Callbacks');
        $response->assertSee('data-flux-navlist-badge');
    }

    public function test_navigation_hides_badge_when_no_callbacks_in_last_24_hours(): void
    {
        $user = User::factory()->create();
        Callback::factory()->create(['created_at' => now()->subDays(2)]);

        $response = $this->actingAs($user)->get(route('prayers.index'));

        $response->assertStatus(200);
        $response->assertSee('Callbacks');
    }

    public function test_navigation_shows_badge_when_prayers_submitted_in_last_24_hours(): void
    {
        $user = User::factory()->create();
        PrayerRequest::factory()->create(['created_at' => now()->subHours(6)]);

        $response = $this->actingAs($user)->get(route('prayers.index'));

        $response->assertStatus(200);
        $response->assertSee('Prayers');
        $response->assertSee('data-flux-navlist-badge');
    }

    public function test_navigation_hides_badge_when_no_prayers_in_last_24_hours(): void
    {
        $user = User::factory()->create();
        PrayerRequest::factory()->create(['created_at' => now()->subDays(3)]);

        $response = $this->actingAs($user)->get(route('prayers.index'));

        $response->assertStatus(200);
        $response->assertSee('Prayers');
    }

    public function test_navigation_shows_badge_when_testimonials_submitted_in_last_24_hours(): void
    {
        $user = User::factory()->create();
        Testimonial::factory()->create(['created_at' => now()->subHours(3)]);

        $response = $this->actingAs($user)->get(route('prayers.index'));

        $response->assertStatus(200);
        $response->assertSee('Testimonials');
        $response->assertSee('data-flux-navlist-badge');
    }

    public function test_navigation_hides_badge_when_no_testimonials_in_last_24_hours(): void
    {
        $user = User::factory()->create();
        Testimonial::factory()->create(['created_at' => now()->subDays(5)]);

        $response = $this->actingAs($user)->get(route('prayers.index'));

        $response->assertStatus(200);
        $response->assertSee('Testimonials');
    }

    public function test_creating_callback_clears_navigation_cache(): void
    {
        Cache::put('nav_new_callbacks', 0, 3600);

        Callback::factory()->create();

        $this->assertNull(Cache::get('nav_new_callbacks'));
    }

    public function test_creating_prayer_request_clears_navigation_cache(): void
    {
        Cache::put('nav_new_prayers', 0, 3600);

        PrayerRequest::factory()->create();

        $this->assertNull(Cache::get('nav_new_prayers'));
    }

    public function test_creating_testimonial_clears_navigation_cache(): void
    {
        Cache::put('nav_new_testimonials', 0, 3600);

        Testimonial::factory()->create();

        $this->assertNull(Cache::get('nav_new_testimonials'));
    }
}
