<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasLikes
{
    public function like(User $user): void
    {
        if ($this->hasLikeBy($user)) {
            return;
        }

        $this->likes()->save(
            new Like(['user_id' => $user->id]),
        );
    }

    public function unlike(User $user): void
    {
        if (!$like = $this->getLikeBy($user)) {
            return;
        }

        $like->delete();
    }

    public function getLikeBy(User $user): ?Like
    {
        /** @var Like $like */
        $like = $this
            ->likes()
            ->where('user_id', $user->id)
            ->first();

        return $like;
    }

    public function hasLikeBy(User $user): bool
    {
        return (bool)$this->getLikeBy($user);
    }

    public function scopeLikedBy(Builder $query, User $user): Builder
    {
        return $query->whereHas('likes', fn($query) => $query
            ->where('user_id', $user->id)
        );
    }

    public function scopeWithLikes(Builder $query, ?User $user, string $alias = 'liked'): Builder
    {
        return $query->withCount([
            'likes',
            "likes as {$alias}" => fn($query) => $query->where('user_id', $user?->getKey()),
        ]);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
