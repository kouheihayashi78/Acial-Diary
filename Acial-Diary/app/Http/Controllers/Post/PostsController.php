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
        $ses_key = $this->session_key . '.home';
        $user = Auth::user();
        $view = view('top');
        $view->with('user', $user);
        return $view;
    }

    /**
     * マイページ画面
     * @param Request $request
     * @return void
     */
    public function ProfileEdit()
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

    /**
     * 画像をリサイズして保存します
     *
     * @param UploadedFile $file アップロードされた画像
     * @return string ファイル名
     */
    public function saveAvatar(UploadedFile $file)
    {
        $tempPath = $this->makeTempPath();
        Image::make($file)->fit(300, 300)->save($tempPath);
        $filePath = Storage::disk('public')->putFile('icons', new File($tempPath));
        
        // 一時ファイルを生成してパスを取得する(makeTempPathメソッド)
        // Intervention Imageを使用して、画像をリサイズ後、一時ファイルに保存。
        // Storageファサードを使用して画像をディスクに保存しています。
        
        return basename($filePath);
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
    }

    public function createPostForm()
    {
        $form = new Form();
        $user = Auth::user();
        $ses_key = $this->session_key . '.create';
        $input = session()->get("{$ses_key}.input", []);


        $view = view('create');
        $view->with('form', $form->buildCreate($input));
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
