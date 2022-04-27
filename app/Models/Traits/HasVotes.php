<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasVotes
{
    public function vote(User $user, bool $direction): void
    {
        if ($this->hasVoteBy($user, $direction)) {
            return;
        }

        $this->unvote($user, !$direction);

        $vote = new Vote([
            'user_id' => $user->getKey(),
            'direction' => $direction,
        ]);

        $this->votes()->save($vote);
    }

    public function unvote(User $user, bool $direction): void
    {
        if (!$vote = $this->getVoteBy($user, $direction)) {
            return;
        }

        $vote->delete();
    }

    public function getVoteBy(User $user, bool $direction): ?Vote
    {
        /** @var Vote $vote */
        $vote = $this
            ->votes()
            ->byUser($user)
            ->where('direction', $direction)
            ->first();

        return $vote;
    }

    public function hasVoteBy(User $user, bool $direction): bool
    {
        return (bool)$this->getVoteBy($user, $direction);
    }

    public function scopeWithVotes(Builder $query, ?User $user): Builder
    {
        return $query->withCount([
            'votes as votes_up_count' => fn($query) => $query->up(),
            'votes as votes_down_count' => fn($query) => $query->down(),
            'votes as voted_up' => fn($query) => $query->byUser($user)->up(),
            'votes as voted_down' => fn($query) => $query->byUser($user)->down(),
        ]);
    }

    public function votes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'voteable');
    }
}
