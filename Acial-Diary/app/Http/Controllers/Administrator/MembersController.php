<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SimpleForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * ユーザー管理コントローラー`
 */
class MembersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');  //変更
    }
    
    protected $session_key = 'members';

    /**
     * 検索一覧画面
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $search = new Search();
        $service = new MembersService();

        $ses_key = $this->session_key . '.search';

        if ($request->has('btnSearch')) {
            $search_val = $request->all();
            //dd($search_val);
            //検索値をセッションに保存
            session()->put($ses_key . '.input', $search_val);
        }

        if ($request->has('btnSearchClear')) {
            session()->forget("{$ses_key}");
        }

        $search_val = session()->get("{$ses_key}.input", []); //セッションに値がない場合は初期化
        //var_dump($search_val);

        //検索フォームを作る
        $form = $search->build($search_val);
        //dd($form);

        //検索結果をDBから取得
        $rows = $service->getList($search_val);

        $view = view('operate.user.list');

        $view->with('rows', $rows); //検索結果
        $view->with('form', $form); //フォーム

        return $view;
    }

    /**
     * 詳細画面処理
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function detail(int $id)
    {
        $form = new Form();
        $service = new MembersService();
        $data = $service->get($id);
        //dd($data);

        if (!$data) {
            return redirect()->route('operate.home');
        }

        $view = view('operate.user.detail');
        $view->with('form', $form->getHtml($data->toArray()));
        //dd($form->getHtml($data->toArray()));

        return $view;
    }

    /**
     * 新規作成　入力画面
     * @param Request $request
     * @return void
     */
    public function regist(Request $request)
    {
        $form = new Form();

        $ses_key = $this->session_key . '.regist';

        $input = session()->get("{$ses_key}.input", []);

        $view = view('operate.user.regist');
        $view->with('form', $form->buildRegist($input));

        return $view;
    }

    /**
     * 新規登録：確認画面処理
     * @param Request $request
     * @return void
     */
    public function regist_confirm(Request $request)
    {
        $form = new Form();

        $ses_key = $this->session_key . '.regist';

        //画像パスの作成、public/tempに保存
        if ($request->has('icon')) {
            $icon_image = $request->file('icon');
            $temp_path = $form->store($icon_image);
        }

        $data = array(
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'icon' => $temp_path ?? '',
        );

        //バリデーション
        $request->validate($form->getRuleRegist($data));

        //セッションに保存
        session()->put("{$ses_key}.input", $data);

        //確認画面表示
        $view = view('operate.user.regist_confirm');
        $view->with('form', $form->getHtml($data));

        return $view;
    }

    /**
     * 新規登録：登録処理
     *
     * @param Request $request
     * @return void
     */
    public function regist_proc(Request $request)
    {
        //dd($request);
        $form = new Form();
        $service = new MembersService();

        $ses_key = "{$this->session_key}.regist";

        //sessionをnullに
        $data = session()->get("{$ses_key}.input", null);
        //dd($data);

        //データがない場合は入力画面に戻る
        if (empty($data)) {
            return redirect()->route('operate.regist');
        }

        //バリデーション
        $form->getRuleRegist($data);

        //登録処理
        $service->regist($data);

        //セッション削除
        session()->forget("{$ses_key}");

        return redirect()->route('operate.regist.complete');
    }

    /**
     * 新規登録：完了画面
     * @param Request $request
     * @return void
     */
    public function regist_complete(Request $request)
    {
        $view = view('operate.admin_complete');

        $view->with('func_name', 'メンバー管理');
        $view->with('mode_name', '新規登録');
        $view->with('back', route('operate.home'));

        return $view;
    }

    /**
     * 更新　入力画面
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $form = new Form();
        $service = new MembersService();

        $ses_key = $this->session_key . '.update';

        if ($id) {
            
            $data = $service->get($id);
            session()->put("{$ses_key}.id", $id);
        }

        $input = session()->get("{$ses_key}.input", []);

        if (!$input) {
            $input = $data->toArray();
        }
        //dd($input);
        $view = view('operate.user.update');
        $view->with('form', $form->build($input));
        //アイコン写真
        $view->with('icon', $form->getHtml($data->toArray()));

        //dd($input);

        return $view;
    }

    /**
     * 更新：確認画面処理
     * @param Request $request
     * @return void
     */
    public function update_confirm(Request $request)
    {
        $form = new Form();
        $service = new MembersService();

        $ses_key = $this->session_key . '.update';

        $temp_path = null;

        //画像パスの作成、public/tempに保存
        if ($request->has('icon')) {
            $icon_image = $request->file('icon');
            $temp_path = $form->store($icon_image);
        }

        $input = array(
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'icon' => $temp_path ?? '',
        );

        //バリデーション
        $request->validate($form->getRule($input));

        //入力値をセッションに保存
        $input = $request->all();
        session()->put("{$ses_key}.input", $input);


        //バリデーション
        $request->validate($form->getRule($input));
        //
        $data = $service->get(session()->get("{$ses_key}.id"));

        //確認画面表示
        $view = view('operate.user.update_confirm');
        $view->with('form', $form->getHtml($input));
        $view->with('data', $data);

        return $view;
    }

    /**
     * 更新：登録処理
     *
     * @param Request $request
     * @return void
     */
    public function update_proc(Request $request)
    {
        $form = new Form();
        $service = new MembersService();

        $ses_key = "{$this->session_key}.update";

        $data = session()->get("{$ses_key}.input", null);

        //データがない場合は入寮画面に戻る
        if (empty($data)) {
            return redirect()->route('operate.update');
        }

        //バリデーション
        $ret = SimpleForm::validation($data, $form->getRule($data));
        if ($ret !== true) {
            //入力画面にリダイレクト
            return redirect()->route('operate.update')->withErrors($ret);
        }

        //登録処理
        $id = session()->get("{$ses_key}.id");
        $service->update($id, $data);

        //セッション削除
        session()->forget("{$ses_key}");

        return redirect()->route('operate.update.complete');
    }

    /**
     * 更新：完了画面
     * @param Request $request
     * @return void
     */
    public function update_complete(Request $request)
    {
        $view = view('operate.user.admin_complete');

        $view->with('func_name', 'メンバー管理');
        $view->with('mode_name', '更新');
        $view->with('back', route('operate.home'));

        return $view;
    }

    /**
     * 削除：確認画面
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function delete_confirm(Request $request, int $id)
    {
        $form = new Form();
        $service = new MembersService();

        $ses_key = "{$this->session_key}.delete";
        //該当データを取得
        $data = $service->get($id);
        if (!$data) {
            return redirect()->route('operate');
        }

        session()->put("{$ses_key}.id", $id); //(users.delete.id)

        //dd($data);
        $view = view('operate.user.delete_confirm');
        $view->with('form', $form->getHtml($data->toArray()));
        // var_dump($data->toArray());
        // var_dump($form->getHtml($data->toArray()));

        return $view;
    }

    /**
     * 更新：登録処理
     *
     * @param Request $request
     * @return void
     */
    public function delete_proc(Request $request)
    {
        $form = new Form();
        $service = new MembersService();

        $ses_key = "{$this->session_key}.delete";

        $id = session()->get("{$ses_key}.id", null);

        //データがない場合は入力画面に戻る
        if (empty($id)) {
            return redirect()->route('operate.home');
        }

        //削除処理：論理削除
        $service->delete($id);

        //セッション削除
        session()->forget("{$ses_key}");

        return redirect()->route('operate.delete.complete');
    }

    /**
     * 更新：完了画面
     * @param Request $request
     * @return void
     */
    public function delete_complete(Request $request)
    {
        $view = view('operate.user.admin_complete');

        $view->with('func_name', 'メンバー管理');
        $view->with('mode_name', '削除');
        $view->with('back', route('operate.home'));

        return $view;
    }
}
