<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::resource('users', UserController::class);

Route::resource('posts', PostController::class);
Route::prefix('posts')->name('posts.')->group(static function (): void {
    Route::patch('{post}/publish', [PostController::class, 'publish'])->name('publish');
    Route::delete('{post}/publish', [PostController::class, 'unpublish'])->name('unpublish');
});

Route::redirect('/', route('admin.users.index'));
