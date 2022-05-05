<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Response\Message;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class UserController extends ApiController
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index(): JsonResponse
    {
        // /** @var string[] $filter */
        // $filter = $request->get('filter', []);

        $items = User::paginate();

        return $this->handleResponse(
            UserResource::collection($items),
            Message::success->value,
        );
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        return $this->handleResponse(
            new UserResource($user),
            Message::success->value,
            Response::HTTP_CREATED,
        );
    }

    public function show(User $user): JsonResponse
    {
        return $this->handleResponse(
            new UserResource($user),
            Message::success->value,
        );
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $user->update($request->validated());

        return $this->handleResponse(
            new UserResource($user),
            Message::success->value,
        );
    }

    public function destroy(User $user): JsonResponse
    {
        $user->tokens()->delete();
        $user->delete();

        return $this->handleResponse(
            message: Message::success->value,
            code: Response::HTTP_NO_CONTENT,
        );
    }
}
