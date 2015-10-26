<?php

namespace App\Http\Controllers;

use App\Service\UserService;
use Socialite;
use Session;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    //google認証
    public function auth()
    {

        return Socialite::with('google')->redirect();

    }

    //google認証リダイレクトページ
    //user情報を格納 / session格納
    public function googleCallback(UserService $userService)
    {
        $google_info = Socialite::with('google')->user();
        $google_id = (int)$google_info->getId();

        //DBからメールアドレスが一致したユーザー情報を取得
        $user = DB::table('users')->where('google_id',$google_id)->first();

        //既に一致するユーザーがいない場合
        if(!$user) {

            $user = $userService->createUser($google_info);

        }

        Session::put('auth', $user->id);
        return redirect('/');

    }

}

