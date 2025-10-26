<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkspaceField extends Model
{
    use HasFactory;

    protected $fillable = [
        'form',
        'key',
        'label',
        'type',
        'required',
        'active',
        'in_analytics',
        'position',
        'placeholder',
        'help_text',
        'options',
        'validation',
        'style',
        'meta',
    ];

    protected $casts = [
        'required' => 'boolean',
        'active' => 'boolean',
        'in_analytics' => 'boolean',
        'options' => 'array',
        'validation' => 'array',
        'style' => 'array',
        'meta' => 'array',
    ];

    public function scopeForm($query, string $form)
    {
        return $query->where('form', $form);
    }

    public static function formFields(string $form = 'coffee_chat')
    {
        return static::query()
            ->form($form)
            ->where('active', true)
            ->orderBy('position')
            ->orderBy('id')
            ->get();
    }
}
