<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Reaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class ReactionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Reaction $reaction): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Reaction $reaction): bool
    {
        return false;
    }

    public function delete(User $user, Reaction $reaction): bool
    {
        return false;
    }

    public function restore(User $user, Reaction $reaction): bool
    {
        return false;
    }

    public function forceDelete(User $user, Reaction $reaction): bool
    {
        return false;
    }
}
