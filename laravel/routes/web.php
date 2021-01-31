<?php

use App\Http\Controllers\LikeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\User\ArticleController as UserArticleController;
use App\Http\Controllers\UserController;
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


Route::get('/health_check', 'HealthCheckController')->name('health_check');

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::resource('/articles', 'ArticleController')->only(['index', 'show']);

Route::prefix('/articles')->name('articles.')->group(function () {
    Route::post('/{article}/like', [LikeController::class, 'like'])->name('like');
    Route::delete('/{article}/unlike', [LikeController::class, 'unlike'])->name('unlike');
});

Route::prefix('/user')->name('user.')->group(function () {
    Route::get('/', [UserController::class, 'show'])->name('show');
    Route::resource('articles', 'User\\ArticleController')->only(['index', 'store', 'update', 'destroy']);
    Route::get('/liked_articles', [UserArticleController::class, 'likedArticle'])->name('liked_index');
});

