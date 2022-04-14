<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class HasAnyRole
{
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->getRoleNames()->count() < 1) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return $next($request);
    }
}
