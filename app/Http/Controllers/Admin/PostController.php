<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\User;
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
            ->withApprovedCommentsCount()
            ->withLikes(new User())
            ->withVotes(new User())
            ->byUserId($request->get('user'));

        if ($request->user()->cannot('posts.view_any')) {
            $items->byUserId($request->user()->getKey());
        }

        if ($search = $request->get('search')) {
            $items->where('title', 'like', "%$search%");
        }

        if ($orders = $request->get('orders')) {
            foreach ($orders as $column => $direction) {
                if ($column === array_key_first($orders)) {
                    $order = "{$column}_{$direction}";
                }

                $items->orderBy($column, $direction);
            }
        }

        $perPage = $request->cookie('posts_perPage');

        return view('admin.posts.index', [
            'items' => $items->paginate($perPage)
                ->appends($request->except('page')),
            'perPage' => $perPage ?? (new Post())->getPerPage(),
            'search' => $search ?? '',
            'order' => $order ?? '',
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
        $post = Post::create($data = $request->validated());

        $post->syncTags($data['tags'] ?? '');

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
        $post->update($data = $request->validated());

        $post->syncTags($data['tags'] ?? '');

        return redirect()->route('admin.posts.index');
    }

    public function publish(Post $post): RedirectResponse
    {
        $this->authorize('publish', $post);

        $post->publish();

        return redirect()->back();
    }

    public function unpublish(Post $post): RedirectResponse
    {
        $this->authorize('publish', $post);

        $post->unpublish();

        return redirect()->back();
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->back();
    }
}
