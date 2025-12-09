<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'event_name',
        'properties',
        'path',
        'referrer',
        'occurred_at',
    ];

    protected $casts = [
        'properties' => 'array',
        'occurred_at' => 'datetime',
    ];
}
