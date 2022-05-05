<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::name('posts.')->group(static function (): void {
    Route::get('/{page?}', [App\Http\Controllers\PostController::class, 'index'])->name('index')->where('page', 'posts');
    Route::get('/posts/{post}', [App\Http\Controllers\PostController::class, 'show'])->name('show');

    Route::prefix('/posts/{post}/comments')->name('comments.')->group(static function (): void {
        Route::post('', [App\Http\Controllers\CommentController::class, 'store'])->name('store');
        Route::put('{comment}', [App\Http\Controllers\CommentController::class, 'update'])->name('update');
        Route::delete('{comment}', [App\Http\Controllers\CommentController::class, 'destroy'])->name('destroy');
    });
});

Route::prefix('users/{user}')->name('users.')->group(static function (): void {
    Route::get('', [App\Http\Controllers\UserProfileController::class, 'show'])->name('show');

    Route::prefix('posts')
        ->name('posts.')
        ->controller(App\Http\Controllers\UserPostController::class)
        ->group(static function (): void {
            Route::get('', 'index')->name('index');
            Route::get('saved', 'saved')->name('saved');
            Route::get('upvoted', 'upvoted')->name('upvoted');
            Route::get('downvoted', 'downvoted')->name('downvoted');
        });

    Route::prefix('comments')
        ->name('comments.')
        ->controller(App\Http\Controllers\UserCommentController::class)
        ->group(static function (): void {
            Route::get('', 'index')->name('index');
            Route::get('saved', 'saved')->name('saved');
            Route::get('upvoted', 'upvoted')->name('upvoted');
            Route::get('downvoted', 'downvoted')->name('downvoted');
        });

    Route::scopeBindings()
        ->prefix('notifications')
        ->name('notifications.')
        ->middleware('auth')
        ->controller(App\Http\Controllers\UserNotificationController::class)
        ->group(static function (): void {
            Route::get('', 'index')->name('index');
            Route::delete('{notification}', 'destroy')->name('destroy');
        });
});

Route::prefix('likes')->name('likes.')->middleware('auth')->group(static function (): void {
    Route::post('{type}/{id}', [App\Http\Controllers\LikeController::class, 'store'])->name('store');
    Route::delete('{type}/{id}', [App\Http\Controllers\LikeController::class, 'destroy'])->name('destroy');
});

Route::prefix('votes')->name('votes.')->middleware('auth')->group(static function (): void {
    Route::post('{type}/{id}', [App\Http\Controllers\VoteController::class, 'store'])->name('store');
    Route::delete('{type}/{id}', [App\Http\Controllers\VoteController::class, 'destroy'])->name('destroy');
});

Auth::routes();
