<?php

namespace App\Http\Controllers;

use Socialite;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class CallbackController extends Controller
{

//    public function __construct(){
//
//    }

    public function googleCallback()
    {
//        Session::put('users', true);
//        var_dump(Socialite::driver('google'));exit;
//        $driver = Socialite::driver('google');
//        $driver->stateless();
//        $user = $driver->user();
//        exit;
        $user = Socialite::with('google')->user();
//        try {
//            $user = Socialite::with('google')->user();
//        } catch (\Exception $e) {
////            var_dump($e);
////            throw $e;
//            return "失敗";
//        }


//        // OAuth Two プロバイダー
//        $token = $user->token;
//
//        // OAuth One プロバイダー
//        $token = $user->token;
//        $tokenSecret = $user->tokenSecret;

        // 全プロバイダー
        $user->getId();
        $user->getNickname();
        $user->getName();
        $user->getEmail();
        $user->getAvatar();
        var_dump($user);
    }

    protected function input()
    {
        return "hogehogehoge";
    }

}

