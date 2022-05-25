<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;

class PostService extends Controller
{
    public function getAll($data = [], $offset = 10)
    {
        $post = Post::query();
        $user = User::query();
        $postModel = new Post();

        $post->where('user_id', '<>', Auth::id());

        if(!empty($data['title'])) $post->where('title', $data['title']);
        if(!empty($data['body'])) $post->where('body', $data['body']);
        if (!empty($data['img'])) $post->where('img', $data['img']);
        if (!empty($data['active'] = 1)) $post->where('active', $data['active']);
        if (!empty($data['publish'] = 1)) $post->where('publish', $data['publish']);

        if (!empty($data['name'])) $user->where('name', $data['name']);

        // もしキーワードが入力されている場合
        if (!empty($data['keyword'])) {
            $keyword = $postModel->getSearch($data['keyword']);
            
            // キーワードが入力されていない場合
        } else{
            return $post->orderby('id','DESC')->paginate($offset);
            exit;
        }
        return $keyword;
    } 

        /**
     * 検索処理
     * @param array $data 検索条件を値を配列で取得
     * @return void
     */
    public function myPostGet($data = [], $offset = 10)
    {
        $post = Post::query();
        $user = User::query();
        $user_id = Auth::id();
        $postModel = new Post();

        $post->where('user_id', $user_id);

        if (!empty($data['title'])) $post->where('title', $data['title']);
        
        if (!empty($data['body'])) $post->where('body', $data['body']);

        if (!empty($data['active'] = 1)) $post->where('active', $data['active']);

        if (!empty($data['publish'] = 1)) $post->where('publish', $data['publish']);

        if (!empty($data['img'])) $post->where('img', $data['img']);

        // if( !empty($data['type']) ) $post->where( 'type', $data['type'] );

        if (!empty($data['name'])) $user->where('name', $data['name']);
        


        return $post->orderby('id','DESC')->paginate($offset);
    }

    public function get(int $id) 
    {
        $data = Post::find($id);
        return $data;
    }

    /**
     * 更新処理 
     * @param [content] $id
     * @param array $data 更新情報を配列で取得
     * @return object
     */
    public function update($id, $data = [])
    {

        $user = Auth::user();
        $recode = Post::where('active', 1)->where('id', $id)->where('user_id', $user['id'])->find($id);
        if (!$recode) return null;

        $recode->fill($data);
        $recode->save();

        return $recode;
    }
    /**
     * 削除処理
     * @param [content] $id
     * @return void
     */
    public function delete($id)
    {
        return Post::where('id', $id)->update(['active' => 2]);
    }
}
