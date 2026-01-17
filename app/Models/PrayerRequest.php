<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrayerRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'prayer',
        'name',
        'is_prayed_for',
        'prayed_at',
    ];

    protected $casts = [
        'is_prayed_for' => 'boolean',
        'prayed_at' => 'datetime',
    ];
}
