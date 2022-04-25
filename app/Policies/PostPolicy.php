<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class PostPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->canAny(['posts.view_any', 'posts.view_own']);
    }

    public function create(User $user): bool
    {
        return $user->can('posts.create');
    }

    public function update(User $user, Post $model): bool
    {
        if ($user->can('posts.update_any')) {
            return true;
        }

        if ($user->can('posts.update_own')) {
            return $user->id === $model->author_id;
        }

        return false;
    }

    public function delete(User $user, Post $model): bool
    {
        if ($user->can('posts.delete_any')) {
            return true;
        }

        if ($user->can('posts.delete_own')) {
            return $user->id === $model->author_id;
        }

        return false;
    }

    public function publish(User $user, Post $model): bool
    {
        if ($user->can('posts.publish_any')) {
            return true;
        }

        if ($user->can('posts.publish_own')) {
            return $user->id === $model->author_id;
        }

        return false;
    }

    public function vote(User $user, Post $model): bool
    {
        if ($user->cannot('posts.vote')) {
            return false;
        }

        return $user->getKey() !== $model->author_id;
    }
}
