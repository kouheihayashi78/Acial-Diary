<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class LikesController extends Controller
{
    //コンストラクタ （このクラスが呼ばれると最初にこの処理をする）
    public function __construct()
    {
        // ログインしていなかったらログインページに遷移する（この処理を消すとログインしなくてもページを表示する）
        $this->middleware('auth');
    }


    // public function createLike(Request $request)
    // {
    //     $like = new Like;
    //     $like->user_id = Auth::user()->id;
    //     $like->post_id = $request->post_id;
    //     $like->save();
    //     $post = new Post;
    //     $id = $post->likedBy(Auth::user())->firstOrFail();
    //     return response()->json($id); 
    // }

    /**
     * いいね！を取り消し
     *
     * @param array $data
     * @return bool
     */
    // public function deleteLike(Request $request)
    // {
    //     // $like = Like::where('post_id', $post_id)->where('user_id', Auth::id())->first();
    //     $like = Like::where('id', $request->like_id)->first();
    //     // 受け取ったHTTPリクエストからIDを判別し、指定のレコードを一つ取得
    //     $like->delete();
    //     return redirect('/');
    // }

    //////////////////
    // これだけで良さそう
    //////////////////
    public function like(Request $request)
    {
        $user_id = Auth::id();
        $post_id = $request->post_id;
        $liked = Like::where('user_id', $user_id)->where('post_id', $post_id)->first(); //3.

        if (!$liked) { //もしこのユーザーがこの投稿にまだいいねしてなかったら
            $like = new Like; //4.Likeクラスのインスタンスを作成
            $like->post_id = $post_id; //Likeインスタンスにpost_id,user_idをセット
            $like->user_id = $user_id;
            $like->save();
        } else { //もしこのユーザーがこの投稿に既にいいねしてたらdelete
            Like::where('post_id', $post_id)->where('user_id', $user_id)->delete();
        }
        //5.この投稿の最新の総いいね数を取得
        $post = new Post;
        $post_likes_count = Post::withCount('likes')->findOrFail($post_id)->likes_count;
        // $post_likes_count = $post->likedBy(Auth::user())->count();
        $param = [
            'post_likes_count' => $post_likes_count,
        ];
        return response()->json($param); //6.JSONデータをjQueryに返す
    }
}
