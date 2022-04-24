<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class LikeController extends Controller
{
    private const MAP = [
        'posts' => \App\Models\Post::class,
        'comments' => \App\Models\Comment::class,
    ];

    public function store(Request $request, string $type, int $id): RedirectResponse|JsonResponse
    {
        if (!array_key_exists($type, self::MAP)) {
            abort(404);
        }

        $model = self::MAP[$type]::findOrFail($id);
        $model->like($request->user());

        if (!$request->wantsJson()) {
            return redirect()->back();
        }

        return new JsonResponse([
            'message' => 'OK',
            'data' => [
                'liked' => true,
                'likesCount' => $model->likes()->count(),
            ],
        ]);
    }

    public function destroy(Request $request, string $type, int $id): RedirectResponse|JsonResponse
    {
        if (!array_key_exists($type, self::MAP)) {
            abort(404);
        }

        $model = self::MAP[$type]::findOrFail($id);
        $model->unlike($request->user());

        if (!$request->wantsJson()) {
            return redirect()->back();
        }

        return new JsonResponse([
            'message' => 'OK',
            'data' => [
                'liked' => false,
                'likesCount' => $model->likes()->count(),
            ],
        ]);
    }
}
