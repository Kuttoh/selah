<?php

namespace App\Models;

use App\Enums\PrayerStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrayerRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'prayer',
        'name',
        'status',
        'prayed_at',
        'prayed_by',
        'public_token',
    ];

    protected $casts = [
        'status' => PrayerStatus::class,
        'prayed_at' => 'datetime',
    ];

    public function prayedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prayed_by');
    }
}
