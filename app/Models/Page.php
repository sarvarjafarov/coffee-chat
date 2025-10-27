<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RalphJSmit\Laravel\SEO\Support\HasSEO;

class Page extends Model
{
    /** @use HasFactory<\Database\Factories\PageFactory> */
    use HasFactory;
    use HasSEO;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function components(): HasMany
    {
        return $this->hasMany(PageComponent::class)->orderBy('position');
    }

    public function component(string $key): ?PageComponent
    {
        return $this->components->firstWhere('key', $key);
    }
}
