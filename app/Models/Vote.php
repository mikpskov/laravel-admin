<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class Vote extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'direction',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'direction' => 'boolean',
    ];

    public function scopeUp(Builder $query): Builder
    {
        return $query->where('direction', true);
    }

    public function scopeDown(Builder $query): Builder
    {
        return $query->where('direction', false);
    }

    public function scopeByUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->getKey());
    }

    public function voteable(): MorphTo
    {
        return $this->morphTo();
    }
}
