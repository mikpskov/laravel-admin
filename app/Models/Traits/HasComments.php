<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property Collection $comments
 */
trait HasComments
{
    public static function bootHasComments()
    {
        /** @var User $user */
        if ($user = auth()->user()) {
            self::creating(fn(self $model) => $model->user_id ??= $user->getKey());
        }
    }

    public function scopeWithApprovedCommentsCount(Builder $query): Builder
    {
        return $query->withCount(['comments' => fn(Builder $query) => $query->approved()]);
    }

    public function comment(User $user, string $body)
    {
        $comment = new Comment([
            'body' => $body,
        ]);

        return $this->comments()->save($comment);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
