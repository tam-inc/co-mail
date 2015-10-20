<?php

namespace App\Http\Controllers;

use Socialite;
use Session;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class CallbackController extends Controller
{

    public function googleCallback()
    {

        try {
            $user = Socialite::with('google')->user();

            $id = $user->getId();

            //todo DBにgoogle認証の情報を入れる
//            $user->getNickname();
//            $user->getName();
//            $user->getEmail();
//            $user->getAvatar();

            Session::put('auth',$id);

            return redirect('/');

        } catch (\Exception $e) {
//            var_dump($e);
//            throw $e;
            //todo エラー処理 HTTPステータスコード
            return "失敗";
        }

    }

}

