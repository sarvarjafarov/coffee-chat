<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'string',
    ];

    public static function value(string $key, $default = null)
    {
        return static::query()->where('key', $key)->value('value') ?? $default;
    }

    public static function updateMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
