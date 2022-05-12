<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreReactionRequest;
use App\Http\Requests\UpdateReactionRequest;
use App\Http\Requests\UpdateVoteRequest;
use App\Models\Enums\ReactionType;
use App\Models\Reaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class ReactionController extends Controller
{
    private const MODEL_MAP = [
        'posts' => \App\Models\Post::class,
        'comments' => \App\Models\Comment::class,
    ];

    public function store(
        Request $request,
        string $reactable_type,
        string $reactable_id,
        string $reaction_type,
    ): RedirectResponse|JsonResponse {
        if (!array_key_exists($reactable_type, self::MODEL_MAP)) {
            abort(404);
        }

        if (!$reactionType = ReactionType::tryFrom($reaction_type)) {
            abort(404);
        }

        $model = self::MODEL_MAP[$reactable_type]::findOrFail($reactable_id);

        $model->addReaction($reactionType);

        if (!$request->wantsJson()) {
            return redirect()->back();
        }

        return new JsonResponse([
            'message' => 'OK',
            'data' => [
                'reacted' => true,
                'reactions_count' => $model->reactions()->byType($reactionType)->count(),
            ],
        ]);
    }

    public function destroy(
        Request $request,
        string $reactable_type,
        string $reactable_id,
        string $reaction_type,
    ): RedirectResponse|JsonResponse {
        if (!array_key_exists($reactable_type, self::MODEL_MAP)) {
            abort(404);
        }

        if (!$reactionType = ReactionType::tryFrom($reaction_type)) {
            abort(404);
        }

        $model = self::MODEL_MAP[$reactable_type]::findOrFail($reactable_id);

        $model->deleteReaction($reactionType);

        if (!$request->wantsJson()) {
            return redirect()->back();
        }

        return new JsonResponse([
            'message' => 'OK',
            'data' => [
                'reacted' => false,
                'reactions_count' => $model->reactions()->byType($reactionType)->count(),
            ],
        ]);
    }
}
