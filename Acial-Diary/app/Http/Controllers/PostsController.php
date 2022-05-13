<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;

class PostsController extends Controller
{
    protected $session_key = 'post';

    public function __construct()
    {
        $this->middleware('auth');
    }

        /**
     * マイページ画面
     * @param Request $request
     * @return void
     */
    public function home(Request $request)
    {
        $ses_key = $this->session_key . '.mypage';
        $user = Auth::user();
        $view = view('top');
        $view = view('top');
        $view->with('user', $user);
        return $view;
    }

    public function showPostDetail($user_id)
    {
    }
    public function createPostForm()
    {
        $ses_key = $this->session_key . '.create';
        $input = session()->get("{$ses_key}.input", []);
        $user = Auth::user();


        return view('create');
    }

    public function createPost()
    {
    }

    public function editPostForm()
    {
    }

    public function editPost()
    {
    }

    public function deletePost()
    {
    }
}
