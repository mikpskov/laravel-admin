<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')
    ->name('auth.')
    ->controller(App\Http\Controllers\Api\AuthController::class)
    ->group(static function (): void {
        Route::get('', 'show')->name('show');
        Route::post('', 'store')->name('store')->withoutMiddleware('auth:sanctum');
        Route::delete('', 'destroy')->name('destroy');
    });

Route::apiResource('users', App\Http\Controllers\Api\UserController::class);
