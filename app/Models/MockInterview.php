<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MockInterview extends Model
{
    /** @use HasFactory<\Database\Factories\MockInterviewFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'interview_type',
        'difficulty',
        'focus_area',
        'scheduled_at',
        'time_zone',
        'duration_minutes',
        'status',
        'partner_name',
        'partner_email',
        'join_url',
        'agenda',
        'notes',
        'feedback',
        'rating',
        'reminder_channels',
        'prep_materials',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'duration_minutes' => 'integer',
        'rating' => 'integer',
        'reminder_channels' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
