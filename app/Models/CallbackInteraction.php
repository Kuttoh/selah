<?php

namespace App\Models;

use App\Enums\CallbackStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CallbackInteraction extends Model
{
    /** @use HasFactory<\Database\Factories\CallbackInteractionFactory> */
    use HasFactory;

    protected $fillable = [
        'callback_id',
        'notes',
        'status',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => CallbackStatus::class,
        ];
    }

    public function callback(): BelongsTo
    {
        return $this->belongsTo(Callback::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
