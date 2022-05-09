<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Queries\PostQuery;
use App\Services\Post\PostManager;
use App\Services\Response\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class PostController extends ApiController
{
    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
    }

    public function index(Request $request): JsonResponse
    {
        $query = new PostQuery();

        if ($request->user()->cannot('posts.view_any')) {
            $query->byUserId($request->user()->getKey());
        }

        return $this->handleResponse(
            PostResource::collection($query->paginate()),
            Message::success->value,
        );
    }

    public function store(
        StorePostRequest $request,
        PostManager $postManager,
    ): JsonResponse {
        $post = $postManager->store($request->validated());

        return $this->handleResponse(
            new PostResource($post),
            Message::success->value,
            Response::HTTP_CREATED,
        );
    }

    public function show(Post $post): JsonResponse
    {
        $post = (new PostQuery($post))->find($post->id);

        return $this->handleResponse(
            new PostResource($post),
            Message::success->value,
        );
    }

    public function update(
        UpdatePostRequest $request,
        PostManager $postManager,
        Post $post,
    ): JsonResponse {
        $post = $postManager->update($post, $request->validated());

        return $this->handleResponse(
            new PostResource($post),
            Message::success->value,
        );
    }

    public function publish(
        PostManager $postManager,
        Post $post,
    ): JsonResponse {
        $this->authorize('publish', $post);

        $postManager->publish($post);

        return $this->handleResponse(
            new PostResource($post),
            Message::success->value,
        );
    }

    public function unpublish(
        PostManager $postManager,
        Post $post,
    ): JsonResponse {
        $this->authorize('publish', $post);

        $postManager->unpublish($post);

        return $this->handleResponse(
            new PostResource($post),
            Message::success->value,
        );
    }

    public function destroy(
        PostManager $postManager,
        Post $post,
    ): JsonResponse {
        $postManager->delete($post);

        return $this->handleResponse(
            message: Message::success->value,
            code: Response::HTTP_NO_CONTENT,
        );
    }
}
