<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home(Request $request)
    {
        return view('home');
    }

    public function showPostDetail($user_id)
    {
    }
    public function createPostForm()
    {
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
