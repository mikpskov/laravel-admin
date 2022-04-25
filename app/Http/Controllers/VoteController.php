<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdateVoteRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

final class VoteController extends Controller
{
    private const MODEL_MAP = [
        'posts' => \App\Models\Post::class,
        'comments' => \App\Models\Comment::class,
    ];

    public function store(UpdateVoteRequest $request, string $type, int $id): RedirectResponse|JsonResponse
    {
        if (!array_key_exists($type, self::MODEL_MAP)) {
            abort(404);
        }

        $direction = $request->validated('direction');

        $model = self::MODEL_MAP[$type]::findOrFail($id);

        $model->vote($request->user(), $direction);

        if (!$request->wantsJson()) {
            return redirect()->back();
        }

        return new JsonResponse([
            'message' => 'OK',
            'data' => [
                'voted' => true,
                'direction' => $direction,
                'countUp' => $countUp = $model->votes()->up()->count(),
                'countDown' => $countDown = $model->votes()->down()->count(),
                'count' => $countUp + $countDown,
                'total' => $countUp - $countDown,
            ],
        ]);
    }

    public function destroy(UpdateVoteRequest $request, string $type, int $id): RedirectResponse|JsonResponse
    {
        if (!array_key_exists($type, self::MODEL_MAP)) {
            abort(404);
        }

        $direction = $request->validated('direction');

        $model = self::MODEL_MAP[$type]::findOrFail($id);
        $model->unvote($request->user(), $direction);

        if (!$request->wantsJson()) {
            return redirect()->back();
        }

        return new JsonResponse([
            'message' => 'OK',
            'data' => [
                'voted' => false,
                'direction' => $direction,
                'countUp' => $countUp = $model->votes()->up()->count(),
                'countDown' => $countDown = $model->votes()->down()->count(),
                'count' => $countUp + $countDown,
                'total' => $countUp - $countDown,
            ],
        ]);
    }
}
