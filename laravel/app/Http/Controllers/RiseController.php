<?php

namespace App\Http\Controllers;

use Socialite;
use Session;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class RiseController extends Controller
{

    public function __construct(){
//        Session::forget('auth');

        //ログイン機構
        //セッションがあるかどうかを判定する
        if (Session::has('auth'))
        {

            return "セッション有り";

        } else {

            $this->beforeFilter(function(){

                return Socialite::with('google')->redirect();

            });

        }

    }

    protected function auth()
    {

        $data = Session::all();
        var_dump($data);
    }


}

