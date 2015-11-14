<?php

namespace App\Http\Controllers;

use App\Service\UserService;
use Socialite;
use Session;

class AuthController extends Controller
{
    use RestControllerTrait;

    //ログインチェック
    public function getUser(UserService $userService)
    {
        //セッションからユーザーIDを取得
        $session = Session::get('auth');

        //セッションが無かったらエラーを返す
        if (!$session) {
            return $this->responseBad([
                "message" => "ログインしていません。",
            ], 403);
        }

        //セッションからユーザー情報を取得
        $user = $userService->getUser($session);

        return $this->responseOk([
            "user" => $user,
        ]);
    }


    //google認証
    public function auth()
    {
        return Socialite::with('google')->redirect();
    }


    //google認証リダイレクトページ
    public function googleCallback(UserService $userService)
    {
        $google_info = Socialite::with('google')->user();
        $google_id = $google_info->getId();

        //googleのIDからユーザー情報を取得
        $user = $userService->getUserByGoogleID($google_id);

        if (!$user) {
            //ユーザー情報を作成する
            $userService->createUser($google_info, $google_id);
            //ユーザーテーブルのユーザーIDをセッションに入れるため、ユーザー情報をユーザーテーブルから取得する必要がある。
            $user = $userService->getUserByGoogleID($google_id);
        }

        //セッションにuserのIDを格納
        Session::put('auth', $user->id);

        //todo area分け
        return redirect('/tokyo');
    }
}
