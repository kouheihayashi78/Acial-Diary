<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PostsController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [PostsController::class, 'home'])->name('home');
    Route::get('/detail', [PostsController::class, 'showPostDetail'])->name('post-detail');
    Route::get('/create', [PostsController::class, 'createPostForm'])->name('create-post');
    Route::post('/create', [PostsController::class, 'createPost'])->name('create-post');
    Route::get('/edit', [PostsController::class, 'editPostForm'])->name('edit-post');
    Route::post('/edit', [PostsController::class, 'editPost'])->name('edit-post');
    Route::post('/delete', [PostsController::class, 'deletePost'])->name('delete-post');
});
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
