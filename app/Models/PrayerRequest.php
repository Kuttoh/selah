<?php

namespace App\Models;

use App\Enums\PrayerStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Cache;

class PrayerRequest extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::created(fn () => Cache::forget('nav_new_prayers'));
    }

    protected $fillable = [
        'prayer',
        'name',
        'status',
        'prayed_at',
        'prayed_by',
        'public_token',
        'answered_at',
    ];

    protected $casts = [
        'status' => PrayerStatus::class,
        'prayed_at' => 'datetime',
        'answered_at' => 'datetime',
    ];

    public function prayedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prayed_by');
    }

    public function testimonial(): HasOne
    {
        return $this->hasOne(Testimonial::class);
    }

    /**
     * Scope to get prayer requests submitted within the last 24 hours.
     */
    public function scopeRecentlySubmitted(Builder $query): Builder
    {
        return $query->where('created_at', '>=', now()->subDay());
    }
}
