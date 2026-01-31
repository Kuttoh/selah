<?php

namespace App\Models;

use App\Enums\CallbackStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Callback extends Model
{
    /** @use HasFactory<\Database\Factories\CallbackFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'service_id',
        'prayer_request_id',
        'public_token',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function prayerRequest(): BelongsTo
    {
        return $this->belongsTo(PrayerRequest::class);
    }

    public function interactions(): HasMany
    {
        return $this->hasMany(CallbackInteraction::class);
    }

    /**
     * Get the current status based on the latest interaction.
     * Returns Pending if no interactions exist.
     */
    protected function currentStatus(): Attribute
    {
        return Attribute::make(
            get: function (): CallbackStatus {
                $latestInteraction = $this->interactions()->latest('id')->first();

                return $latestInteraction?->status ?? CallbackStatus::Pending;
            }
        );
    }
}
