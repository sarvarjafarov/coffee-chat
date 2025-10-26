<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Channel extends Model
{
    /** @use HasFactory<\Database\Factories\ChannelFactory> */
    use HasFactory;

    protected $fillable = [
        'slug',
        'label',
        'description',
    ];

    public function coffeeChats(): BelongsToMany
    {
        return $this->belongsToMany(CoffeeChat::class)
            ->withTimestamps()
            ->withPivot('details');
    }
}
