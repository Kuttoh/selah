<?php

namespace Tests\Feature\Admin;

use App\Enums\PrayerStatus;
use App\Livewire\Admin\PrayerList;
use App\Models\PrayerRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;
use Tests\TestCase;

class PrayerListTest extends TestCase
{
    use RefreshDatabase;

    public function test_shows_all_prayers_by_default(): void
    {
        PrayerRequest::factory()->create(['status' => PrayerStatus::Received]);
        PrayerRequest::factory()->create(['status' => PrayerStatus::Prayed]);

        Livewire::test(PrayerList::class)
            ->assertSet('filter', 'all')
            ->assertViewHas('prayers', function (LengthAwarePaginator $prayers) {
                return $prayers->total() === 2 && $prayers->perPage() === 10;
            });
    }

    public function test_filters_unprayed_prayers(): void
    {
        PrayerRequest::factory()->create(['status' => PrayerStatus::Received]);
        PrayerRequest::factory()->create(['status' => PrayerStatus::Prayed]);

        Livewire::test(PrayerList::class)
            ->call('setFilter', 'unprayed')
            ->assertSet('filter', 'unprayed')
            ->assertViewHas('prayers', function (LengthAwarePaginator $prayers) {
                return $prayers->total() === 1 && $prayers->first()->status === PrayerStatus::Received;
            });
    }

    public function test_filters_prayed_prayers(): void
    {
        PrayerRequest::factory()->create(['status' => PrayerStatus::Received]);
        PrayerRequest::factory()->create(['status' => PrayerStatus::Prayed]);

        Livewire::test(PrayerList::class)
            ->call('setFilter', 'prayed')
            ->assertSet('filter', 'prayed')
            ->assertViewHas('prayers', function (LengthAwarePaginator $prayers) {
                return $prayers->total() === 1 && $prayers->first()->status === PrayerStatus::Prayed;
            });
    }

    public function test_marking_as_prayed_updates_list(): void
    {
        $prayer = PrayerRequest::factory()->create(['status' => PrayerStatus::Received]);

        Livewire::test(PrayerList::class)
            ->call('show', $prayer->id)
            ->call('markAsPrayed')
            ->assertSet('showModal', false)
            ->assertSet('selectedPrayer', null);

        $prayer->refresh();
        $this->assertSame($prayer->status, PrayerStatus::Prayed);
        $this->assertNotNull($prayer->prayed_at);
    }

    public function test_filter_persists_after_marking_as_prayed(): void
    {
        PrayerRequest::factory()->create(['status' => PrayerStatus::Received]);
        $prayer = PrayerRequest::factory()->create(['status' => PrayerStatus::Received]);

        Livewire::test(PrayerList::class)
            ->call('setFilter', 'unprayed')
            ->assertViewHas('prayers', function (LengthAwarePaginator $prayers) {
                return $prayers->total() === 2;
            })
            ->call('show', $prayer->id)
            ->call('markAsPrayed')
            ->assertSet('filter', 'unprayed')
            ->assertViewHas('prayers', function (LengthAwarePaginator $prayers) {
                return $prayers->total() === 1;
            }); // Should now have only 1 unprayed
    }

    public function test_orders_pending_before_prayed_then_newest_first(): void
    {
        $pendingOlder = PrayerRequest::factory()->create([
            'status' => PrayerStatus::Received,
            'created_at' => now()->subDays(2),
        ]);
        $pendingNewer = PrayerRequest::factory()->create([
            'status' => PrayerStatus::Received,
            'created_at' => now()->subDay(),
        ]);
        $prayedNewest = PrayerRequest::factory()->create([
            'status' => PrayerStatus::Prayed,
            'created_at' => now(),
        ]);
        $prayedOlder = PrayerRequest::factory()->create([
            'status' => PrayerStatus::Prayed,
            'created_at' => now()->subDays(3),
        ]);

        Livewire::test(PrayerList::class)
            ->assertViewHas('prayers', function (LengthAwarePaginator $prayers) use ($pendingNewer, $pendingOlder, $prayedNewest, $prayedOlder) {
                $ids = $prayers->pluck('id')->all();

                return $ids === [
                    $pendingNewer->id,
                    $pendingOlder->id,
                    $prayedNewest->id,
                    $prayedOlder->id,
                ];
            });
    }
}
