<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingAttribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'marketing_event_id',
        'conversion_type',
        'model',
        'session_id',
        'user_id',
        'source',
        'medium',
        'campaign',
        'credit',
        'occurred_at',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'credit' => 'float',
    ];

    public function event()
    {
        return $this->belongsTo(MarketingEvent::class, 'marketing_event_id');
    }
}
