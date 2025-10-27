<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavigationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'url',
        'location',
        'order',
        'icon',
        'style',
    ];

    protected $casts = [
        'style' => 'array',
    ];

    public function scopePrimary($query)
    {
        return $query->where('location', 'primary');
    }

    public function scopeFooter($query)
    {
        return $query->where('location', 'footer');
    }
}
