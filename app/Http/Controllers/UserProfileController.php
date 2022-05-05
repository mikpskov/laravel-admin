<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

final class UserProfileController extends Controller
{
    public function __construct()
    {
        Paginator::defaultView('posts.partials.pagination');
    }

    public function show(User $user): View
    {
        return view('users.show', compact('user'));
    }
}
