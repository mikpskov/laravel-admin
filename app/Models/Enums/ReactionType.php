<?php

declare(strict_types=1);

namespace App\Models\Enums;

enum ReactionType: string
{
    case upvote = 'upvote';
    case downvote = 'downvote';
    case save = 'save';
}
