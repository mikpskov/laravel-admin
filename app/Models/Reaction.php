<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Enums\ReactionType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class Reaction extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'type',
    ];

    protected $casts = [
        'type' => ReactionType::class,
    ];

    public function scopeByType(Builder $query, ReactionType $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeByUser(Builder $query, ?User $user): Builder
    {
        return $query->where('user_id', $user?->getKey());
    }

    public function reactable(): MorphTo
    {
        return $this->morphTo();
    }
}
