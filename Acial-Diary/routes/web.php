<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Post\PostsController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\Auth\LoginController;

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



Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    Route::any('/', [PostsController::class, 'home'])->name('home');
    Route::any('/mypost', [PostsController::class, 'myPost'])->name('my-post');
    Route::any('/mylike', [PostsController::class, 'myLike'])->name('my-like');
    Route::get('/mypage', [PostsController::class, 'profileEdit'])->name('profile');
    Route::post('/mypage', [PostsController::class, 'editProfile'])->name('edit-profile');
    Route::get('/detail{user_id}', [PostsController::class, 'showPostDetail'])->name('post-detail');
    Route::get('/create', [PostsController::class, 'createPostForm'])->name('create-post');
    Route::post('/create/proc', [PostsController::class, 'createPostProc'])->name('create-post-proc');
    Route::get('/create/complete', [PostsController::class, 'createPostComplete'])->name('create-post-complete');
    Route::any('/edit{user_id}', [PostsController::class, 'editPostForm'])->name('edit-post');
    Route::post('/edit', [PostsController::class, 'editPost'])->name('edit-post-proc');
    Route::get('/edit/complete', [PostsController::class, 'editPostComplete'])->name('edit-post-complete');
    Route::any('/delete{user_id}', [PostsController::class, 'deletePost'])->name('delete-post');
    Route::get('/delete/complete', [PostsController::class, 'deletePostComplete'])->name('delete-post-complete');

    // いいね機能
    Route::post('post/likes', [LikesController::class, 'like'])->name('create-like');
    // Route::post('like', [LikesController::class, 'deleteLike'])->name('delete-like');
});
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
