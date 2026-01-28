<?php

namespace App\Http\Controllers;

use App\Enums\PrayerStatus;
use App\Models\PrayerRequest;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class PublicPrayerProgressController
{
    /**
     * Display prayer progress by public token.
     */
    public function show(string $token): View
    {
        $prayer = PrayerRequest::query()
            ->where('public_token', $token)
            ->firstOrFail();

        $prayerText = (string) $prayer->prayer;

        if ($prayer->status === PrayerStatus::Received) {
            $statusMessage = 'Your prayer has not yet been marked as prayed.';
        } else {
            $date = Carbon::parse($prayer->prayed_at)->format('F j, Y g:i A');
            $statusMessage = "Your prayer was prayed for on {$date}";
        }

        return view('prayers.progress', [
            'prayerText' => $prayerText,
            'statusMessage' => $statusMessage,
        ]);
    }
}
