<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function getSearch($data)
    {
        // keywordがなければ、全て取得

            $hits = $this::select('posts.*')
                ->leftJoin('users', 'users.id', '=', 'posts.user_id')

                ->where('users.name', 'LIKE', "%{$data}%")
                ->orwhere('posts.title', 'LIKE', "%{$data}%")
                ->orwhere('posts.body', 'LIKE', "%{$data}%")
                ->where('posts.active', 1) // activeカラムは最後に記述しないとうまくいかないのでここに入力
                ->orderby('posts.created_at', 'DESC')
                ->get();

            return $hits;
        
    }
}
