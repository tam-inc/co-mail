<?php

namespace App\Http\Controllers;

use Socialite;
use Session;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Illuminate\Http\Request;

class RiseController extends Controller
{

    public function __construct(){
//        Session::put('users',"hoge");
        //ログイン機構
        //セッションがあるかどうかを判定する
        if (Session::has('users'))
        {

            return "セッション有り";

        } else {
            $request->session()->get('key');

            $this->beforeFilter(function(){

                return Socialite::with('google')->redirect();

            });

        }

    }

    protected function auth()
    {
        return "hoge";
    }


}

