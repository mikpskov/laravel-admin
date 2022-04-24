<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\Comment;
use App\Models\User;
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
            self::creating(fn(self $model) => $model->author_id ??= $user->getKey());
        }
    }

    public function comment(User $user, string $body)
    {
        $comment = new Comment([
            'body' => $body,
        ]);

        // if (($user instanceof Commentator) ? !$user->needsCommentApproval($this) : false) {
        //     $comment->approved_at = now();
        // }

        return $this->comments()->save($comment);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
