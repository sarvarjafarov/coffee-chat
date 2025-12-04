<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'plan',
        'stripe_customer_id',
        'stripe_subscription_id',
        'plan_expires_at',
        'oauth_provider',
        'oauth_id',
        'avatar_url',
        'xp_total',
        'weekly_chat_goal',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'plan_expires_at' => 'datetime',
            'xp_total' => 'integer',
            'weekly_chat_goal' => 'integer',
        ];
    }

    public function isPremium(): bool
    {
        return $this->plan === 'premium' && (! $this->plan_expires_at || $this->plan_expires_at->isFuture());
    }

    public function isFree(): bool
    {
        return ! $this->isPremium();
    }

    public function menuItems()
    {
        return $this->hasMany(WorkspaceMenuItem::class);
    }
}
