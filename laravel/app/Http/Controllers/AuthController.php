<?php

namespace App\Http\Controllers;

use App\Service\UserService;
use Socialite;
use Session;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{

    //ログインしているかチェック
    public function login(UserService $userService){

        $session = Session::get('auth');

        if($session){

            $user = $userService->getUser($session);

            return JsonResponse::create([
                "status" => "OK",
                "user" => $user,
            ],200);

        } else {

            return JsonResponse::create([
                "status" => "BAD",
                "message" => "ログインしていません。",
            ],403);

        }

    }

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

        //DBからgoogle_idが一致したユーザー情報を取得
        $user = $userService->getUser($google_id);

        //既に一致するユーザーがいない場合
        if(!$user) {

            $user = $userService->createUser($google_info);

        }

        //todo ユーザーIDをトークン化する
        Session::put('auth', $google_id);
        return redirect('/');

    }

}

