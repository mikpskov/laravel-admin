<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

final class PostController extends Controller
{
    public function index(Request $request): View
    {
        Paginator::defaultView('posts.partials.pagination');

        $items = Post::query()
            ->withApprovedCommentsCount()
            ->withLikes($request->user())
            ->withVotes($request->user())
            ->with('tags')
            ->byTag($request->get('tag'))
            ->published()
            ->latest();

        if ($search = $request->get('search')) {
            $items->where('title', 'like', "%$search%");
        }

        return view('posts.index', [
            'items' => $items->paginate(10)  // todo: move perPage to config
                ->appends($request->except('page')),
            'search' => $search ?? '',
            'title' => __('Posts'),
        ]);
    }

    public function show(Request $request, int $id): View
    {
        /** @var Post $post */
        $post = Post::query()
            ->withApprovedCommentsCount()
            ->withLikes($request->user())
            ->withVotes($request->user())
            ->findOrFail($id);

        $comments = $post->comments()
            ->withLikes($request->user())
            ->withVotes($request->user())
            ->approved()
            ->get();

        return view('posts.show', [
            'item' => $post,
            'comments' => $comments,
        ]);
    }
}
