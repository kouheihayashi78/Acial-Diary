<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'img',
        'user_id',
        'publish',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function likes()
    {
        return $this->hasMany('App\Models\Like', 'post_id');
    }

    public function getSearch($data)
    {
        // keywordがなければ、全て取得

        $hits = $this::select('posts.*')
            ->leftJoin('users', 'users.id', '=', 'posts.user_id')

            ->where('users.name', 'LIKE', "%{$data}%")
            ->orwhere('posts.title', 'LIKE', "%{$data}%")
            ->orwhere('posts.body', 'LIKE', "%{$data}%")
            ->where('posts.active', 1) 
            ->orderby('posts.created_at', 'DESC')
            ->get();

        return $hits;
    }

    public function getLike($user)
    {
        // keywordがなければ、全て取得

        $like = $this::select('posts.*')
            ->leftJoin('likes', 'likes.post_id', '=', 'posts.id')

            ->where('likes.user_id', $user->id) 
            ->where('posts.active', 1) // activeカラムは最後に記述しないとうまくいかないのでここに入力
            ->orderby('posts.created_at', 'DESC')
            ->get();

        return $like;
    }


    public function likedBy($user)
    {
        return Like::where('user_id', $user->id)->where('post_id', $this->id);
    }


    function createLike($post_id)
    {
        $like = new Like;
        // $like->user_id = Auth::user()->id;
        $like->post_id = $post_id;
        $like->all()->count();

        return $like;
    }

    /**
     * いいね！を取り消し
     *
     * @param array $data
     * @return bool
     */
    function deleteLike($post_id)
    {
        $like = Like::where('post_id', $post_id)->where('user_id', Auth::id())->first();
        $like->delete();

        return redirect()->back();
    }
}
