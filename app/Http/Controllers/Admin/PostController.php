<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
    }

    public function index(Request $request): View
    {
        $items = Post::query()
            ->byUserId($request->get('user'))
            ->latest();

        if ($request->user()->cannot('posts.view_any')) {
            $items->byUserId($request->user()->getKey());
        }

        if ($search = $request->get('search')) {
            $items->where('title', 'like', "%$search%");
        }

        $headers = ['id', 'user_id', 'title', 'published_at'];

        $perPage = $request->cookie('posts_perPage');

        return view('admin.posts.index', [
            'headers' => $headers,
            'items' => $items->paginate($perPage, $headers)
                ->appends($request->except('page')),
            'perPage' => $perPage ?? (new Post())->getPerPage(),
            'search' => $search ?? '',
            'title' => __('Posts'),
            'createUrl' => route('admin.posts.create'),
        ]);
    }

    public function create(): View
    {
        return view('admin.posts.edit', [
            'item' => null,
        ]);
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        Post::create($request->validated());

        return redirect()->route('admin.posts.index');
    }

    public function show(Post $post): View
    {
        abort(404);
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.edit', [
            'item' => $post,
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $post->update($request->validated());

        return redirect()->route('admin.posts.index');
    }

    public function publish(Post $post): RedirectResponse
    {
        $this->authorize('publish', $post);

        $post->publish();

        return redirect()->route('admin.posts.index');
    }

    public function unpublish(Post $post): RedirectResponse
    {
        $this->authorize('publish', $post);

        $post->unpublish();

        return redirect()->route('admin.posts.index');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->back();
    }
}
