<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkHealthAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'monthly_unique_contacts',
        'warm_intros_last_quarter',
        'average_follow_up_days',
        'industry_diversity',
        'relationship_strength',
        'score',
        'summary',
        'recommendations',
    ];

    protected $casts = [
        'recommendations' => 'array',
        'average_follow_up_days' => 'float',
    ];
}
