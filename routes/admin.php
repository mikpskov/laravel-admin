<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::resource('users', UserController::class);

Route::redirect('/', route('admin.users.index'));
