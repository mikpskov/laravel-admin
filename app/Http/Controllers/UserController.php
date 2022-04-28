<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

final class UserController extends Controller
{
    public function __construct()
    {
        Paginator::defaultView('posts.partials.pagination');
    }

    public function show(User $user): View
    {
        return view('users.show', compact('user'));
    }

    public function posts(Request $request, User $user): View
    {
        $items = $user->posts()
            ->withApprovedCommentsCount()
            ->withLikes($request->user())
            ->withVotes($request->user())
            ->with('tags')
            ->published()
            ->latest()
            ->paginate(10);

        return view('users.posts', compact('user', 'items'));
    }

    public function comments(Request $request, User $user): View
    {
        $items = $user->comments()
            ->withLikes($request->user())
            ->withVotes($request->user())
            ->approved()
            ->latest()
            ->paginate(20);

        return view('users.comments', compact('user', 'items'));
    }

    public function savedPosts(Request $request, User $user): View
    {
        $this->authorize('saved', $user);

        $items = Post::query()
            ->withApprovedCommentsCount()
            ->likedBy($user)
            ->withLikes($request->user())
            ->withVotes($request->user())
            ->with('tags')
            ->published()
            ->latest()
            ->paginate(10);

        return view('users.posts', compact('user', 'items'));
    }

    public function savedComments(Request $request, User $user): View
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

    public function upvotedPosts(Request $request, User $user): View
    {
        $this->authorize('upvoted', $user);

        $items = Post::query()
            ->withApprovedCommentsCount()
            ->upvotedBy($user, true)
            ->withLikes($request->user())
            ->withVotes($request->user())
            ->with('tags')
            ->published()
            ->latest()
            ->paginate(10);

        return view('users.posts', compact('user', 'items'));
    }

    public function upvotedComments(Request $request, User $user): View
    {
        $this->authorize('upvoted', $user);

        $items = Comment::query()
            ->upvotedBy($user, true)
            ->withLikes($request->user())
            ->withVotes($request->user())
            ->approved()
            ->latest()
            ->paginate(20);

        return view('users.posts', compact('user', 'items'));
    }

    public function downvotedPosts(Request $request, User $user): View
    {
        $this->authorize('downvoted', $user);

        $items = Post::query()
            ->withApprovedCommentsCount()
            ->downvotedBy($user)
            ->withLikes($request->user())
            ->withVotes($request->user())
            ->with('tags')
            ->published()
            ->latest()
            ->paginate(10);

        return view('users.posts', compact('user', 'items'));
    }

    public function downvotedComments(Request $request, User $user): View
    {
        $this->authorize('downvoted', $user);

        $items = Comment::query()
            ->downvotedBy($user)
            ->withLikes($request->user())
            ->withVotes($request->user())
            ->approved()
            ->latest()
            ->paginate(20);

        return view('users.posts', compact('user', 'items'));
    }
}
