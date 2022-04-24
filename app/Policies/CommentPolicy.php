<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class CommentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('comments.view_any');
    }

    public function create(User $user): bool
    {
        return $user->canany(['comments.create', 'comments.create_auto_approve']);
    }

    public function update(User $user, Comment $comment): bool
    {
        if ($user->cannot('comments.update')) {
            return false;
        }

        return $user->getKey() === $comment->author_id;
    }

    public function delete(User $user, Comment $comment): bool
    {
        if ($user->can('comments.delete_any')) {
            return true;
        }

        if ($user->can('comments.delete_own')) {
            return $user->getKey() === $comment->author_id;
        }

        return false;
    }

    public function approve(User $user, Comment $comment): bool
    {
        return $user->can('comments.approve');
    }
}
