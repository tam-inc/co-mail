<?php

namespace App\Http\Controllers;

use Socialite;
use Session;
use Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;

class RiceController extends Controller
{

//    public function __construct(){

//        //ログイン機構
//        //セッションがあるかどうかを判定する
//        if (Session::has('auth'))
//        {
//
//            return "hoge";
//
//        } else {
//
//            $this->beforeFilter(function(){
//
//                return Socialite::with('google')->redirect();
//
//            });


//            $come_status == 0;
//
//            return json_encode([
//                "status" => "OK",
//                "come_status" => 0
//            ]);

//        }
//    }
//        if ($come_status == 1)
//        {
//
//            $this->beforeFilter(function(){
//
//                return Socialite::with('google')->redirect();
//
//            });
//
//        } else if($come_status == 2){
//
//            $id = Session::get('auth');
//
//            $user = DB::table('users')->where('google_id', $id)->first();
//
//            $rise_user = DB::table('rise')->where('rise_id', $user->id)->first();
//
//            return json_encode([
//                "status" => "OK",
//                "come_status" => 2,
//                "user" => [
//                    "id"=>$user->id,
//                    "name"=>$rise_user->name,
//                    "email"=>$rise_user->email,
//                    "rise"=>$rise_user->volume,
//                ]
//            ]);
//
//        } else if($come_status == 3){
//
//            return json_encode([
//                "status" => "OK",
//                "come_status" => 3,
//                "user" => [
//                    "id"=>$user->id,
//                    "name"=>$rise_user->name,
//                    "email"=>$rise_user->email,
//                    "rise"=>$rise_user->volume,
//                ]
//            ]);
//
//        }
//        else if($come_status == 4){
//
//            return json_encode([
//                "status" => "OK",
//                "come_status" => 4,
//            ]);
//
//        }

//    }

    protected function get_user()
    {

        $id = Session::get('auth');

        $user = DB::table('users')->where('id', $id)->first();

        return json_encode([
            "session" => $id,
            "status" => "OK",
            "user" => [
                "id"=>$user->id,
                "name"=>$user->name,
                "email"=>$user->email,
            ]
        ]);

    }

    protected function apply()
    {

        $apply_id = Request::input('id');
        $apply_rice = Request::input('rice');

        //todo model作成​
        DB::table('rice')->insert([
            'user_id' => $apply_id,
            'date' => \Carbon\Carbon::now()->toDateTimeString(),
            'volume' => $apply_rice,
            'winner' => 0,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);

        return json_encode([
            "status" => "OK",
            "come_status" => 2,
            "user" => [
                "id" => 1,
                "name" => "ホゲ太郎",
                "email" => "hoge@sample.co.jp",
                "rice" => 1.5
            ],
            "limit_time" => 1234556
        ]);

    }

}

