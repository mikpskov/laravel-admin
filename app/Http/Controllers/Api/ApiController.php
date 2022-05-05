<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use ArrayAccess;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiController extends Controller
{
    final public function handleResponse(
        ArrayAccess|array $data = [],
        string $message = '',
        int $code = Response::HTTP_OK,
    ): JsonResponse {
        $res = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($res, $code);
    }

    final public function handleError(
        ArrayAccess|array $data = [],
        string $message = '',
        int $code = Response::HTTP_BAD_REQUEST,
    ): JsonResponse {
        $res = [
            'success' => false,
            'message' => $message,
            'errors' => $data,
        ];

        return response()->json($res, $code);
    }
}
