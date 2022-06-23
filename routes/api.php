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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'posts'], function () {
    Route::get('/{category}/{sort}/{page}/{size}', [\App\Http\Controllers\Api\PostController::class, 'index'])->name('posts.index');
    Route::get('/{post}', [\App\Http\Controllers\Api\PostController::class, 'show'])->name('posts.show');
});

Route::group(['prefix' => 'categories'], function () {
    Route::get('/', [\App\Http\Controllers\Api\CategoryController::class, 'index'])->name('categories.index');
    Route::get('/{post}', [\App\Http\Controllers\Api\CategoryController::class, 'show'])->name('categories.show');
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login'])->name('api.auth.login');
    Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register'])->name('api.auth.register');
    Route::get('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout'])->name('api.auth.logout')->middleware('auth:sanctum');
});
