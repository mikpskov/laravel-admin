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
            ->with('author:id,name')
            ->withCount('likes')
            ->joinLikedBy($request->user())
            ->published()
            ->latest();

        if ($search = $request->get('search')) {
            $items->where('title', 'like', "%$search%");
        }

        $headers = ['id', 'author_id', 'title', 'body', 'created_at'];

        return view('posts.index', [
            'headers' => $headers,
            'items' => $items->paginate(10, $headers)  // todo: move perPage to config
                ->appends($request->except('page')),
            'search' => $search ?? '',
            'title' => __('Posts'),
        ]);
    }

    public function show(Post $post): View
    {
        return view('posts.show', [
            'item' => $post,
        ]);
    }
}
