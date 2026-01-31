<?php

namespace App\View\Composers;

use App\Models\Callback;
use App\Models\PrayerRequest;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class NavigationComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $view->with([
            'newCallbacksCount' => $this->getNewCallbacksCount(),
            'newPrayersCount' => $this->getNewPrayersCount(),
            'newTestimonialsCount' => $this->getNewTestimonialsCount(),
        ]);
    }

    /**
     * Get the count of callbacks submitted within the last 24 hours.
     */
    protected function getNewCallbacksCount(): int
    {
        return Cache::remember('nav_new_callbacks', 3600, function () {
            return Callback::recentlySubmitted()->count();
        });
    }

    /**
     * Get the count of prayer requests submitted within the last 24 hours.
     */
    protected function getNewPrayersCount(): int
    {
        return Cache::remember('nav_new_prayers', 3600, function () {
            return PrayerRequest::recentlySubmitted()->count();
        });
    }

    /**
     * Get the count of testimonials submitted within the last 24 hours.
     */
    protected function getNewTestimonialsCount(): int
    {
        return Cache::remember('nav_new_testimonials', 3600, function () {
            return Testimonial::recentlySubmitted()->count();
        });
    }
}
