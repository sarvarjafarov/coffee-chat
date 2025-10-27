<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'publishable_key',
        'secret_key',
        'price_id',
        'webhook_secret',
    ];

    public static function current(): self
    {
        return static::query()->latest()->firstOrCreate([]);
    }
}
