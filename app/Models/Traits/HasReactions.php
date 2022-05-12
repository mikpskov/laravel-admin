<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\Enums\ReactionType;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasReactions
{
    public function addReaction(ReactionType $type, ?User $user = null): void
    {
        /** @var User $user */
        $user ??= auth()->user();

        if ($this->hasReaction($type, $user)) {
            return;
        }

        if ($type === ReactionType::upvote) {
            $this->deleteReaction(ReactionType::downvote, $user);
        } elseif ($type === ReactionType::downvote) {
            $this->deleteReaction(ReactionType::upvote, $user);
        }

        $this->reactions()->create([
            'user_id' => $user->getKey(),
            'type' => $type,
        ]);
    }

    public function deleteReaction(ReactionType $type, ?User $user = null): void
    {
        /** @var User $user */
        $user ??= auth()->user();

        if (!$reaction = $this->getReaction($type, $user)) {
            return;
        }

        $reaction->delete();
    }

    public function getReaction(ReactionType $type, ?User $user = null): ?Reaction
    {
        /** @var User $user */
        $user ??= auth()->user();

        /** @var Reaction $reaction */
        $reaction = $this
            ->reactions()
            ->byUser($user)
            ->byType($type)
            ->first();

        return $reaction;
    }

    public function hasReaction(ReactionType $type, ?User $user = null): bool
    {
        return (bool)$this->getReaction($type, $user);
    }

    public function scopeReacted(Builder $query, ReactionType $type, ?User $user = null): Builder
    {
        /** @var User $user */
        $user ??= auth()->user();

        return $query->whereHas('reactions', fn($query) => $query
            ->byUser($user)
            ->byType($type)
        );
    }

    public function scopeWithReactions(Builder $query, ReactionType $type, ?User $user = null): Builder
    {
        /** @var User $user */
        $user ??= auth()->user();

        return $query->withCount([
            "reactions as reactions_{$type->value}_count" => fn($query) => $query->byType($type),
            "reactions as reacted_{$type->value}" => fn($query) => $query->byUser($user)->byType($type),
        ]);
    }

    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }
}
