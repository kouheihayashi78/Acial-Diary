<?php

namespace App\Http\Controllers\Administrator;

use App\Http\TakemiLibs\CommonService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MembersService
{
    /**
     * 検索処理
     * @param array $data 検索条件を値を配列で取得
     * @return void
     */
    public function getList($data = [], $offset = 30)
    {
        $db = User::query();

        if (!empty($data['name'])) $db->where('name', 'LIKE', "%{$data['name']}%");

        if (!empty($data['email'])) $db->where('email', 'LIKE', "%{$data['email']}%");

        if (!empty($data['active'])) $db->where('active', 'LIKE', "%{$data['active']}%");

        if (!empty($data['type'] = 1)) $db->where('type', $data['type']);
        // 管理者は表示しないようにする

        return $db->paginate(10);
    }

    /**
     * 1件のデータ取得
     * @param integer $id
     * @return object
     */
    public function get(int $id)
    {
        $data = User::find($id);

        return $data;
    }

    /**
     * 新規登録処理
     * @param array $data 登録する情報を配列で取得`
     * @return void
     */
    public function regist($data = [])
    {
        $file_path = null;
        //dd($data);

        //画像を移動
        if ($data['icon']) {

            $file_path = str_replace('temp/members', 'members/' . $data['name'], $data['icon']);
            Storage::move($data['icon'], $file_path);
        }
        //dd($file_path);

        $data = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'icon' => $file_path ?? '',
            'active' => 1
        ]);

        return $data;
    }

    /**
     * 更新処理 
     * @param [type] $id
     * @param array $data 更新情報を配列で取得
     * @return object
     */
    public function update($id, $data = [])
    {
        $recode = User::find($id);
        if (!$recode) return null;
        
        $recode->fill(['password' => Hash::make($data['password'])]);
        // アップデートの時はfillが便利！！！！fillを使うと一発
        // 普通はプロパティ毎に入れる値を書いてあげる必要があるから。
        $recode->save();

        return $recode;
    }

    /**
     * 削除処理
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        //論理削除：active=2にする
        $delete_date = User::find($id);

        if ($delete_date->active === 1) {
            $delete_date->active = 2;
            $delete_date->save();
        }
    }
}
