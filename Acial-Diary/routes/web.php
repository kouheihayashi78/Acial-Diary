<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Post\PostsController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Administrator\MembersController;
use App\Http\Controllers\Administrator;

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

//管理者ページ
Route::group(['middleware' => 'auth:admin', 'prefix' => 'operate', 'as' => 'operate.'], function () {
    Route::any('/regist', [MembersController::class, 'regist'])->name('.regist');
    Route::post('/regist/confirm', [MembersController::class, 'regist_confirm'])->name('regist.confirm');
    Route::post('/regist/proc', [MembersController::class, 'regist_proc'])->name('regist.proc');
    Route::any('/regist/complete', [MembersController::class, 'regist_complete'])->name('regist.complete');

    Route::post('/update/confirm', [MembersController::class, 'update_confirm'])->name('update.confirm');
    Route::post('/update/proc', [MembersController::class, 'update_proc'])->name('update.proc');
    Route::any('/update/complete', [MembersController::class, 'update_complete'])->name('update.complete');
    Route::any('/update/{id}', [MembersController::class, 'update'])->name('update');

    Route::post('/delete/proc', [MembersController::class, 'delete_proc'])->name('delete.proc');
    Route::any('/delete/complete', [MembersController::class, 'delete_complete'])->name('delete.complete');
    Route::any('/delete/{id}', [MembersController::class, 'delete_confirm'])->name('delete.confirm');

    Route::any('/member/{id}', [MembersController::class, 'detail'])->name('detail');

    Route::any('/member', [MembersController::class, 'index'])->name('home');

    //記事管理
    Route::any('post', [PostController::class, 'index'])->name('post');
    Route::post('post/delete/proc', [PostController::class, 'delete_proc'])->name('post.delete.proc');
    Route::any('post/delete/complete', [PostController::class, 'delete_complete'])->name('post.delete.complete');
    Route::any('post/delete/{id}', [PostController::class, 'delete_confirm'])->name('post.delete.confirm');
});

Route::get('login/admin', [AdminLoginController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('login/admin', [AdminLoginController::class, 'login']);

Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::post('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
