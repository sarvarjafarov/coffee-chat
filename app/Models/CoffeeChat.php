<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CoffeeChat extends Model
{
    /** @use HasFactory<\Database\Factories\CoffeeChatFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'contact_id',
        'position_title',
        'scheduled_at',
        'time_zone',
        'location',
        'status',
        'duration_minutes',
        'is_virtual',
        'summary',
        'key_takeaways',
        'next_steps',
        'notes',
        'rating',
        'extras',
        'reminder_sent_at',
        'completed_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'is_virtual' => 'boolean',
        'duration_minutes' => 'integer',
        'rating' => 'integer',
        'extras' => 'array',
        'reminder_sent_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class)
            ->withTimestamps()
            ->withPivot('details');
    }

    public function followUpTasks(): HasMany
    {
        return $this->hasMany(FollowUpTask::class);
    }
}
