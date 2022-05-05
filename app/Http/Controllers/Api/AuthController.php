<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\AuthRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Response\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

final class AuthController extends ApiController
{
    private const TOKEN_NAME = 'token_name';

    public function store(AuthRequest $request): Response
    {
        $data = $request->validated();

        if (!Auth::attempt($data)) {
            return $this->handleError(
                message: 'Invalid login details',
                code: Response::HTTP_UNAUTHORIZED,
            );
        }

        /** @var User $user */
        $user = Auth::user();

        $token = $user->createToken(self::TOKEN_NAME)->plainTextToken;

        return $this->handleResponse(
            [
                'access_token' => $token,
                'token_type' => 'Bearer',
            ],
            Message::success->value,
        );
    }

    public function show(Request $request): Response
    {
        return $this->handleResponse(
            new UserResource($request->user()),
            Message::success->value,
        );
    }

    public function destroy(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        /** @var PersonalAccessToken $token */
        $token = $user->currentAccessToken();

        $token->delete();

        return $this->handleResponse(
            message: Message::success->value,
            code: Response::HTTP_NO_CONTENT,
        );
    }
}
