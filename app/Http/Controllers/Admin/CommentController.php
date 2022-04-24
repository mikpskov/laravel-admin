<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class CommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment');
    }

    public function index(Request $request): View
    {
        $items = Comment::query()
            ->with(['author', 'post'])
            ->latest();

        if ($search = $request->get('search')) {
            $items->where(function (Builder $query) use ($search) {
                $query->where('id', 'like', "%$search%");
                $query->orWhere('body', 'like', "%$search%");
            });
        }

        if ($authorId = $request->get('author')) {
            $items->byAuthorId($authorId);
        }

        if ($postId = $request->get('post')) {
            $items->byPostId($postId);
        }

        $perPage = $request->cookie('comments_perPage');

        return view('admin.comments.index', [
            'items' => $items->paginate($perPage)
                ->appends($request->except('page')),
            'perPage' => $perPage ?? (new Comment())->getPerPage(),
            'search' => $search ?? '',
            'title' => __('Comments'),
        ]);
    }

    public function approve(Comment $comment): RedirectResponse
    {
        $this->authorize('approve', $comment);

        $comment->approve();

        return redirect()->route('admin.comments.index');
    }

    public function disapprove(Comment $comment): RedirectResponse
    {
        $this->authorize('approve', $comment);

        $comment->disapprove();

        return redirect()->route('admin.comments.index');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return redirect()->back();
    }
}
