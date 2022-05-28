<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User as ModelsUser;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // 認証に成功した
            return redirect()->intended('dashboard');
        }
    }



    /**
     * ユーザーを探す条件を指定する
     *
     * @param  \Illuminate\Http\Request $request
     * @return Response
     */
    protected function credentials(Request $request)
    {
        return array_merge(
            $request->only($this->username(), 'password'), // 標準の条件
            ['type' => 1], // 追加条件
            ['active' => 1] // 追加条件
        );
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        // ログイン時に入力されたメールアドレスからユーザーを探す
        $user = ModelsUser::where('email', $request->email)->first();
        // もし該当するユーザーが存在すれば
        if ($user) {
            // ユーザーがアカウント停止状態なら
            if ($user->active == 2) {
                throw ValidationException::withMessages([
                    $this->username() => [trans('auth.stop_active')],
                ]);
                // アカウント停止状態ではないのなら（パスワード打ち間違い）
            } else {
                throw ValidationException::withMessages([
                    $this->username() => [trans('auth.failed')],
                ]);
            }
            // 該当するユーザーがいないのなら（メールアドレス打ち間違い）    
        } else {
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.failed')],
            ]);
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}