<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

final class UserPostController extends Controller
{
    public function __construct()
    {
        Paginator::defaultView('posts.partials.pagination');
    }

    public function index(Request $request, User $user): View
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

    public function saved(Request $request, User $user): View
    {
        $this->authorize('saved', $user);

        $items = Post::query()
            ->likedBy($user)
            ->withApprovedCommentsCount()
            ->withLikes($request->user())
            ->withVotes($request->user())
            ->with('tags')
            ->published()
            ->latest()
            ->paginate(10);

        return view('users.posts', compact('user', 'items'));
    }

    public function upvoted(Request $request, User $user): View
    {
        $this->authorize('upvoted', $user);

        $items = Post::query()
            ->upvotedBy($user)
            ->withApprovedCommentsCount()
            ->withLikes($request->user())
            ->withVotes($request->user())
            ->with('tags')
            ->published()
            ->latest()
            ->paginate(10);

        return view('users.posts', compact('user', 'items'));
    }

    public function downvoted(Request $request, User $user): View
    {
        $this->authorize('downvoted', $user);

        $items = Post::query()
            ->downvotedBy($user)
            ->withApprovedCommentsCount()
            ->withLikes($request->user())
            ->withVotes($request->user())
            ->with('tags')
            ->published()
            ->latest()
            ->paginate(10);

        return view('users.posts', compact('user', 'items'));
    }
}
