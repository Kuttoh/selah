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

        if ($prayer->status === PrayerStatus::Answered) {
            $statusMessage = $prayer->answered_at
                ? "Your prayer was answered on {$prayer->answered_at->format('F j, Y')}"
                : 'Your prayer has been answered!';
        } elseif ($prayer->status === PrayerStatus::Prayed) {
            $date = Carbon::parse($prayer->prayed_at)->format('F j, Y g:i A');
            $statusMessage = "Your prayer was prayed for on {$date}";
        } else {
            $statusMessage = 'Your prayer has not yet been marked as prayed.';
        }

        return view('prayers.progress', [
            'prayerText' => $prayerText,
            'statusMessage' => $statusMessage,
            'publicToken' => $token,
        ]);
    }
}
