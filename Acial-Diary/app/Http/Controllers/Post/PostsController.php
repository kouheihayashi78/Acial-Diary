<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Mypage\Profile\EditRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Post\Form;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Image;

class PostsController extends Controller
{
    protected $session_key = 'post';

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * ホーム画面
     * @param Request $request
     * @return void
     */
    public function home(Request $request)
    {
        $service = new PostService();
        $ses_key = $this->session_key . '.home';
        $user = Auth::user();

        if ($request->has('btnSearch')) {
            $search_val = $request->all();
            session()->put($ses_key . '.input', $search_val);
        }
        if($request->has('btnSearchClear')){
            session()->forget("{$ses_key}");
        }

        $search_val = session()->get("{$ses_key}.input", []);
        // $form = $search->build($search_val);
        // $def['active'] = __('define.info.type');
        $rows = $service->getAll($search_val);
        $posts = Post::withCount('likes')->get();

        $view = view('top');

        $view->with('rows', $rows);

        $view->with('user', $user);

        $view->with('posts', $posts);

        return $view;
    }

    /**
     * 自分の投稿画面
     * @param Request $request
     * @return void
     */
    public function myPost(Request $request)
    {
        $service = new PostService();
        $ses_key = $this->session_key . '.mypost';
        $user = Auth::user();

        if ($request->has('btnSearch')) {
            $search_val = $request->all();
            session()->put($ses_key . '.input', $search_val);
        }
        if($request->has('btnSearchClear')){
            session()->forget("{$ses_key}");
        }

        $search_val = session()->get("{$ses_key}.input", []);
        // $form = $search->build($search_val);
        // $def['active'] = __('define.info.type');
        $rows = $service->myPostGet($search_val);
        $posts = Post::withCount('likes')->get();

        $view = view('mypost');

        $view->with('rows', $rows);

        $view->with('user', $user);

        $view->with('posts', $posts);

        return $view;
    }

    /**
     * いいね一覧稿画面
     * @param Request $request
     * @return void
     */
    public function myLike(Request $request)
    {
        $service = new PostService();
        $ses_key = $this->session_key . '.mypost';
        $user = Auth::user();

        if ($request->has('btnSearch')) {
            $search_val = $request->all();
            session()->put($ses_key . '.input', $search_val);
        }
        if($request->has('btnSearchClear')){
            session()->forget("{$ses_key}");
        }

        $search_val = session()->get("{$ses_key}.input", []);
        // $form = $search->build($search_val);
        // $def['active'] = __('define.info.type');
        $rows = $service->getLikes($user);

        $view = view('like');

        $view->with('rows', $rows);

        $view->with('user', $user);


        return $view;
    }

    /**
     * マイページ画面
     * @param Request $request
     * @return void
     */
    public function profileEdit()
    {
        $user = Auth::user();
        
        return view('mypage', compact('user', $user));
    }

    public function editProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->except('icon');
        $fileName = null;
        //dd($request->file('icon'));

        if ($request->has('icon')) {

            $fileName = $this->saveAvatar($request->file('icon'));
            $user->icon = $fileName;
        }
        //dd($fileName);
        $data = array(
            'name' => $request->name,
            'icon' => $fileName,
        );

        $user->name = $data['name'];
        $user->icon = $data['icon'];
        $user->save();

        return redirect()->back()->with('status', 'プロフィールを変更しました。');
    }


    public function saveAvatar($file)
    {
        if($file) {
            date_default_timezone_set('Asia/Tokyo');
            $originalName = $file->getClientOriginalName();
            
            $temp_path = $file->storeAs('public/icons', $originalName);
            $read_temp_path = Url('') . '/' . str_replace('public/', 'storage/', $temp_path);
        }
        
        // $tempPath = $this->makeTempPath();
        // Image::make($file)->fit(300, 300)->save($tempPath);
        // $filePath = Storage::disk('public')->putFile('icons', new File($tempPath));
        // 一時ファイルを生成してパスを取得する(makeTempPathメソッド)
        // Intervention Imageを使用して、画像をリサイズ後、一時ファイルに保存。
        // Storageファサードを使用して画像をディスクに保存しています。
        
        return basename($read_temp_path);
    }

    /**
     * 一時的なファイルを生成してパスを返します。
     *
     * @return string ファイルパス
     */
    private function makeTempPath(): string
    {
        $tmp_fp = tmpfile(); // 以下のコードで一時ファイルを生成
        $meta   = stream_get_meta_data($tmp_fp); //以下のコードでファイルのメタ情報を取得
        return $meta["uri"];
    }

    public function showPostDetail($user_id)
    {
        $form = new Form();
        $service = new PostService();
        $user = Auth::user();
        $data = $service->get($user_id);
        if (!$data) {
            return redirect()->route('home');
        }
        $rows = $service->myPostGet($data);
        
        $view = view('detail');
        
        //dd($form->getHtml($data->toArray()));
        $view->with('rows', $rows);
        $view->with('user', $user);
        $view->with('form', $form->getHtml($data->toArray()));
        return $view;
    }

    public function createPostForm()
    {
        $form = new Form();
        $user = Auth::user();
        $ses_key = $this->session_key . '.create';
        $input = session()->get("{$ses_key}.input", []);


        $view = view('create');
        $view->with('form', $form->buildCreate($input));
        $view->with('user', $user);
        return $view;
    }


    public function createPostProc(Request $request)
    {
        $form = new Form();
        $user = Auth::id();

        $ses_key = "{$this->session_key}.regist";
        $read_temp_path = null;
        $data = $request->except('img');
        if($request->has('img')) {
            date_default_timezone_set('Asia/Tokyo');
            $originalName = $request->file('img')->getClientOriginalName();
            
            $temp_path = $request->file('img')->storeAs('public/temp', $originalName);
            $read_temp_path = Url('') . '/' . str_replace('public/', 'storage/', $temp_path);
        }

        $data = array(
            'title' => $request->title,
            'body' => $request->body,
            'img' => $read_temp_path ?? '',
            'active' => $request->active,
            'user_id' => $user,
            'active' => $request->active,
        );
        session()->put("{$ses_key}.input", $data);

        if (empty($data)) {
            return redirect()->back();
        }

        $form->postValidates($data);
        $data = Post::create($data);
        session()->forget("{$ses_key}");
        return redirect()->route('create-post-complete');
    }

    public function createPostComplete()
    {
        $view = view('complete');
        $view->with('mode_name', '新規登録');
        $view->with('back', route('home'));
        return $view;
    }

    public function editPostForm($user_id)
    {
        $form = new Form();
        $service = new PostService();
        $user = Auth::user();

        $ses_key = $this->session_key . '.update';
        if (!$user) {
            return redirect()->route('login');
        }

        if ($user_id) {
            $rows = $service->get($user_id);
            session()->put("{$ses_key}.id", $user_id);
        }

        $input = session()->get("{$ses_key}.input", []);
        if (!$input) {
            $input = $rows->toArray();
        }
        $view = view('update');
        $view->with('form', $form->buildCreate($input));
        $view->with('user', $user);

        return $view;
    }

    public function editPost(Request $request)
    {
        $form = new Form();
        $service = new PostService();

        $ses_key = "{$this->session_key}.update";

        $data = $request->all();

        //データがない場合は入力画面に戻る
        if (empty($data)) {
            return redirect()->route('edit-post');
        }

        $read_temp_path = null;

        $data['img'] = $request->except('img');
        //dd($request->file('img'));
        //$imagefile = $request->file('img');
        //storage/app/public/tempファイルに保存
        if ($request->has('img')) {
            date_default_timezone_set('Asia/Tokyo');
            $originalName = $request->file('img')->getClientOriginalName();
            
            $temp_path = $request->file('img')->storeAs('public/temp', $originalName);
            $read_temp_path = Url('') . '/' . str_replace('public/', 'storage/', $temp_path);
        }

        $data['img'] = $read_temp_path ?? '';

        //バリデーション
        $form->postValidates($data);

        //登録処理
        $id = session()->get("{$ses_key}.id");
        $service->update($id, $data);

        //セッション削除
        session()->forget("{$ses_key}");

        return redirect()->route('edit-post-complete');
    }

    public function editPostComplete()
    {
        $view = view('complete');
        $view->with('mode_name', '記事編集');
        $view->with('back', route('home'));
        return $view;
    }

    public function deletePost(int $id)
    {
        $service = new PostService();
        $ses_key = "{$this->session_key}.delete";
        $data = $service->get($id);

        if(empty($data)) {
            return redirect()->route('post-detail', $id);
        }

        $service->delete($id);
        session()->forget("{$ses_key}");
        return redirect()->route('delete-post-complete');
    }

    public function deletePostcomplete(Request $request)
    {
        $view = view('complete');
        $view->with('func_name', 'お知らせ管理');
        $view->with('mode_name', '記事削除');
        $view->with('back', route('home'));

        return $view;
    }
}
