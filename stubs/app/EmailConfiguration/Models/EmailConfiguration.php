<?php

namespace App\EmailConfiguration\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailConfiguration extends Model
{
    public function getTable(): string
    {
        return config('email-configuration.table', 'email_configurations');
    }

    protected $fillable = [
        'name',
        'subject',
        'slug',
        'html_content',
        'text_content',
        'variables',
        'is_active',
        'type',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(static::resolveUserModel(), 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(static::resolveUserModel(), 'updated_by');
    }

    public function getCreatedByDisplayAttribute(): ?string
    {
        return $this->resolveUserDisplay($this->createdBy);
    }

    public function getUpdatedByDisplayAttribute(): ?string
    {
        return $this->resolveUserDisplay($this->updatedBy);
    }

    protected function resolveUserDisplay(?Model $user): ?string
    {
        if ($user === null) {
            return null;
        }

        if (isset($user->name) && $user->name !== '') {
            return (string) $user->name;
        }

        if (isset($user->email) && $user->email !== '') {
            return (string) $user->email;
        }

        return '#'.$user->getKey();
    }

    public static function resolveUserModel(): string
    {
        $explicit = config('email-configuration.user_model');

        if (is_string($explicit) && $explicit !== '') {
            return $explicit;
        }

        $provider = config('auth.defaults.provider', 'users');
        $model = config("auth.providers.{$provider}.model");

        if (is_string($model) && $model !== '') {
            return $model;
        }

        return 'App\\Models\\User';
    }
}
