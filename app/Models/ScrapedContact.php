<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScrapedContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'search_signature',
        'source',
        'first_name',
        'last_name',
        'name',
        'position',
        'company',
        'team',
        'email',
        'profile_url',
        'location',
        'avatar_url',
        'metadata',
        'scraped_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'scraped_at' => 'datetime',
    ];
}
