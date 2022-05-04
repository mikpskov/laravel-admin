<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Contracts\View\View;

final class UserNotificationController extends Controller
{
    public function index(Request $request, User $user): View
    {
        $this->authorize('notifications', $user);

        $query = $user->notifications()
            ->latest();

        if (!$request->get('read')) {
            $query->unread();
        }

        $items = $query->paginate(50);

        return view('users.notifications', compact('user', 'items'));
    }

    public function destroy(
        Request $request,
        User $user,
        DatabaseNotification $notification
    ): RedirectResponse|JsonResponse {
        $this->authorize('delete', $notification);

        $notification->markAsRead();

        if (!$request->wantsJson()) {
            return back();
        }

        return new JsonResponse([
            'message' => 'OK',
            'data' => [
                'read' => true,
            ],
        ]);
    }
}
