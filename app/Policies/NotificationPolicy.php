<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Notifications\DatabaseNotification;

final class NotificationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, User $model): bool
    {
        return $user->is($model);
    }

    public function delete(User $user, DatabaseNotification $notification): bool
    {
        return $user->is($notification->notifiable);
    }
}
