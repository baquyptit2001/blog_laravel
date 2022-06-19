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

Route::group(['middleware' => ['isLoggedIn']], function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');
    Route::group(['prefix' => 'posts'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\PostController::class, 'index'])->name('posts.index');
        Route::get('/create', [\App\Http\Controllers\Admin\PostController::class, 'create'])->name('posts.create');
        Route::post('/create', [\App\Http\Controllers\Admin\PostController::class, 'store'])->name('posts.store');
        Route::get('/{post}', [\App\Http\Controllers\Admin\PostController::class, 'show'])->name('posts.show');
        Route::get('/edit/{post}', [\App\Http\Controllers\Admin\PostController::class, 'edit'])->name('posts.edit');
        Route::post('/edit/{post}', [\App\Http\Controllers\Admin\PostController::class, 'update'])->name('posts.update');
        Route::get('/delete/{post}', [\App\Http\Controllers\Admin\PostController::class, 'destroy'])->name('posts.destroy');
    });
    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
        Route::get('/create', [\App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create');
        Route::post('/create', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
        Route::get('/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'show'])->name('categories.show');
        Route::get('/edit/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit');
        Route::post('/edit/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
        Route::get('/delete/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');
    });
});


Route::group(['prefix' => 'accounts'], function () {
    Route::get('/login', [\App\Http\Controllers\Admin\AuthController::class, 'login_page'])->name('auth.login.page');
    Route::post('/login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->name('auth.login');
    Route::get('/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('auth.logout');
});

Route::group(['prefix' => 'laravel-filemanager'], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

