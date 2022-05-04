<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Comment;
use App\Notifications\NewCommentNotification;
use Illuminate\Support\Facades\Notification;

final class CommentObserver
{
    public function created(Comment $comment): void
    {
        Notification::send($comment->post->user, new NewCommentNotification($comment));
    }
}
