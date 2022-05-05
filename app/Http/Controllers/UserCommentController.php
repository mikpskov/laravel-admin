<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

final class UserCommentController extends Controller
{
    public function __construct()
    {
        Paginator::defaultView('posts.partials.pagination');
    }

    public function index(Request $request, User $user): View
    {
        $items = $user->comments()
            ->withLikes($request->user())
            ->withVotes($request->user())
            ->approved()
            ->latest()
            ->paginate(20);

        return view('users.comments', compact('user', 'items'));
    }

    public function saved(Request $request, User $user): View
    {
        $this->authorize('saved', $user);

        $items = Comment::query()
            ->likedBy($user)
            ->withLikes($request->user())
            ->withVotes($request->user())
            ->approved()
            ->latest()
            ->paginate(20);

        return view('users.comments', compact('user', 'items'));
    }

    public function upvoted(Request $request, User $user): View
    {
        $this->authorize('upvoted', $user);

        $items = Comment::query()
            ->upvotedBy($user)
            ->withLikes($request->user())
            ->withVotes($request->user())
            ->approved()
            ->latest()
            ->paginate(20);

        return view('users.comments', compact('user', 'items'));
    }

    public function downvoted(Request $request, User $user): View
    {
        $this->authorize('downvoted', $user);

        $items = Comment::query()
            ->downvotedBy($user)
            ->withLikes($request->user())
            ->withVotes($request->user())
            ->approved()
            ->latest()
            ->paginate(20);

        return view('users.comments', compact('user', 'items'));
    }
}
