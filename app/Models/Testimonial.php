<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Testimonial extends Model
{
    /** @use HasFactory<\Database\Factories\TestimonialFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::created(fn () => Cache::forget('nav_new_testimonials'));
    }

    protected $fillable = [
        'prayer_request_id',
        'content',
        'display_name',
        'is_public',
        'is_approved',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'is_approved' => 'boolean',
        ];
    }

    public function prayerRequest(): BelongsTo
    {
        return $this->belongsTo(PrayerRequest::class);
    }

    /**
     * Scope to get only approved public testimonials.
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_public', true)
            ->where('is_approved', true)
            ->whereNotNull('content');
    }

    /**
     * Scope to get pending testimonials awaiting approval.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('is_public', true)
            ->where('is_approved', false);
    }

    /**
     * Scope to get testimonials submitted within the last 24 hours.
     */
    public function scopeRecentlySubmitted(Builder $query): Builder
    {
        return $query->where('created_at', '>=', now()->subDay());
    }
}
