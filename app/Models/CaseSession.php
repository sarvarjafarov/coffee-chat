<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseSession extends Model
{
    /** @use HasFactory<\Database\Factories\CaseSessionFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'case_study_id',
        'custom_title',
        'status',
        'scheduled_at',
        'started_at',
        'completed_at',
        'time_zone',
        'duration_minutes',
        'self_scores',
        'reflection',
        'notes',
        'llm_feedback_opt_in',
        'llm_feedback',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'duration_minutes' => 'integer',
        'self_scores' => 'array',
        'llm_feedback_opt_in' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function caseStudy(): BelongsTo
    {
        return $this->belongsTo(CaseStudy::class);
    }
}
