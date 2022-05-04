<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class NewCommentNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Comment $comment,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'comment_id' => $this->comment->id,
            'post_id' => $this->comment->post->id,
            'post_title' => $this->comment->post->title,
            'user_id' => $this->comment->user->id,
            'user_name' => $this->comment->user->name,
        ];
    }
}
