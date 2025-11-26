<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CaseStudy extends Model
{
    /** @use HasFactory<\Database\Factories\CaseStudyFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'case_type',
        'industry',
        'difficulty',
        'estimated_duration_minutes',
        'summary',
        'prompt',
        'exhibits',
        'is_active',
    ];

    protected $casts = [
        'exhibits' => 'array',
        'is_active' => 'boolean',
        'estimated_duration_minutes' => 'integer',
    ];

    public function sessions(): HasMany
    {
        return $this->hasMany(CaseSession::class);
    }
}
