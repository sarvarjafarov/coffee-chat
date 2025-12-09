<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingTouchpoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'source',
        'medium',
        'campaign',
        'content',
        'term',
        'referrer',
        'landing_page',
    ];
}
