<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property User $user
 */
trait HasUser
{
    public static function bootHasUser()
    {
        self::creating(fn(self $model) => $model->user_id ??= auth()->user()?->getKey());
    }

    public function initializeHasUser(): void
    {
        $this->mergeCasts([
            $this->getUserIdColumn() => 'integer',
        ]);
    }

    public function scopeByUserId(Builder $query, ?int $userId = null): Builder
    {
        if ($userId === null) {
            return $query;
        }

        return $query->where($this->getUserIdColumn(), $userId);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, $this->getUserIdColumn());
    }

    public function getUserIdColumn(): string
    {
        return defined('static::PUBLISHED_AT') ? static::USER_ID : 'user_id';
    }
}
