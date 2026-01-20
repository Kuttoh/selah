<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrayerRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'prayer',
        'name',
        'is_prayed_for',
        'prayed_at',
        'prayed_by',
    ];

    protected $casts = [
        'is_prayed_for' => 'boolean',
        'prayed_at' => 'datetime',
    ];

    public function prayedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prayed_by');
    }
}
