<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FollowerController;
use App\Http\Controllers\Api\UserListController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::fallback(static function () {
    return response()->json([
        'message' => 'URL Not Found.'
    ], 404);
});

Route::group(['prefix' => 'auth'], static function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group(['middleware' => ['jwt.auth']], static function () {
        Route::post('refresh-token', [AuthController::class, 'refreshToken']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::get('users', UserListController::class);

// Follower route
Route::group(['prefix' => 'user', 'middleware' => ['jwt.auth']], static function () {
    Route::post('{user}/follow', [FollowerController::class, 'follow']);
    Route::post('{user}/unfollow', [FollowerController::class, 'unfollow']);
    Route::get('followers', [FollowerController::class, 'followers']);
});