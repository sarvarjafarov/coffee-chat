<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowUpTask extends Model
{
    /** @use HasFactory<\Database\Factories\FollowUpTaskFactory> */
    use HasFactory;

    protected $fillable = [
        'coffee_chat_id',
        'user_id',
        'title',
        'notes',
        'due_at',
        'status',
        'completed_at',
        'reminder_sent_at',
    ];

    protected $casts = [
        'due_at' => 'datetime',
        'completed_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
    ];

    public function coffeeChat(): BelongsTo
    {
        return $this->belongsTo(CoffeeChat::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markCompleted(): void
    {
        $this->forceFill([
            'status' => 'completed',
            'completed_at' => now(),
        ])->save();
    }
}
