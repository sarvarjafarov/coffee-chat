<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageComponent extends Model
{
    /** @use HasFactory<\Database\Factories\PageComponentFactory> */
    use HasFactory;

    protected $fillable = [
        'page_id',
        'key',
        'title',
        'subtitle',
        'content',
        'media',
        'meta',
        'style',
        'position',
    ];

    protected $casts = [
        'meta' => 'array',
        'style' => 'array',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
