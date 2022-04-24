<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;

final class CommentController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function store(StoreCommentRequest $request, Post $post): RedirectResponse
    {
        $this->authorize('create', Comment::class);

        /** @var Comment $comment */
        $comment = $post->comments()->create($request->validated());

        if ($request->user()->can('comments.create_auto_approve')) {
            $comment->approve();
        }

        return redirect(route('posts.show', $post) . "#comment-{$comment->getKey()}");
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UpdateCommentRequest $request, Post $post, int $id): RedirectResponse
    {
        /** @var Comment $comment */
        $comment = $post->comments()->findOrFail($id);

        $this->authorize('update', $comment);

        $comment->update($request->validated());

        return redirect(route('posts.show', $post) . "#comment-{$comment->getKey()}");
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Post $post, int $id): RedirectResponse
    {
        /** @var Comment $comment */
        $comment = $post->comments()->findOrFail($id);

        $this->authorize('delete', $comment);

        $comment->delete();

        return redirect(route('posts.show', $post) . '#comments');
    }
}
