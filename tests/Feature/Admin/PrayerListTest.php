<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\PrayerList;
use App\Models\PrayerRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PrayerListTest extends TestCase
{
    use RefreshDatabase;

    public function test_shows_all_prayers_by_default(): void
    {
        PrayerRequest::factory()->create(['is_prayed_for' => false]);
        PrayerRequest::factory()->create(['is_prayed_for' => true]);

        Livewire::test(PrayerList::class)
            ->assertSet('filter', 'all')
            ->assertCount('prayers', 2);
    }

    public function test_filters_unprayed_prayers(): void
    {
        PrayerRequest::factory()->create(['is_prayed_for' => false]);
        PrayerRequest::factory()->create(['is_prayed_for' => true]);

        Livewire::test(PrayerList::class)
            ->call('setFilter', 'unprayed')
            ->assertSet('filter', 'unprayed')
            ->assertCount('prayers', 1);
    }

    public function test_filters_prayed_prayers(): void
    {
        PrayerRequest::factory()->create(['is_prayed_for' => false]);
        PrayerRequest::factory()->create(['is_prayed_for' => true]);

        Livewire::test(PrayerList::class)
            ->call('setFilter', 'prayed')
            ->assertSet('filter', 'prayed')
            ->assertCount('prayers', 1);
    }

    public function test_marking_as_prayed_updates_list(): void
    {
        $prayer = PrayerRequest::factory()->create(['is_prayed_for' => false]);

        Livewire::test(PrayerList::class)
            ->call('show', $prayer->id)
            ->call('markAsPrayed')
            ->assertSet('showModal', false)
            ->assertSet('selectedPrayer', null);

        $prayer->refresh();
        $this->assertTrue($prayer->is_prayed_for);
        $this->assertNotNull($prayer->prayed_at);
    }

    public function test_filter_persists_after_marking_as_prayed(): void
    {
        PrayerRequest::factory()->create(['is_prayed_for' => false]);
        $prayer = PrayerRequest::factory()->create(['is_prayed_for' => false]);

        Livewire::test(PrayerList::class)
            ->call('setFilter', 'unprayed')
            ->assertCount('prayers', 2)
            ->call('show', $prayer->id)
            ->call('markAsPrayed')
            ->assertSet('filter', 'unprayed')
            ->assertCount('prayers', 1); // Should now have only 1 unprayed
    }
}
