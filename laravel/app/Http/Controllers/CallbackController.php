<?php

namespace App\Http\Controllers;

use Socialite;
use Session;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;

class CallbackController extends Controller
{

    public function googleCallback()
    {

        $google_info = Socialite::with('google')->user();
        $google_id = (int)$google_info->getId();

        //todo model作成
        //DBからメールアドレスが一致したユーザー情報を取得
        $user = DB::table('users')->where('google_id',$google_id)->first();

        //既に一致するユーザーがいない場合
        if(!$user) {

            DB::table('users')->insert([
                'google_id' => $google_id,
                'name' => $google_info->getName(),
                'email' => $google_info->getEmail(),
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
            ]);

            $user = DB::table('users')->where('google_id',$google_id)->first();

            Session::put('auth', $user->id);

            return redirect('/');

        }
        //既に一致するユーザーがいる場合
        else {

            Session::put('auth', $user->id);

            return redirect('/');

        }
//        try {
//
//            $google_info = Socialite::with('google')->user();
//            $google_id = $google_info->getId();
//
//            //todo model作成
//            //DBからメールアドレスが一致したユーザー情報を取得
//            $user = DB::table('users')->where('google_id',$google_id)->first();
//
//            //既に一致するユーザーがいない場合
//            if(!$user_exist) {
//
//                DB::table('users')->insert([
//                    'google_id' => $google_id,
//                    'name' => $google_info->getName(),
//                    'email' => $google_info->getEmail(),
//                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
//                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
//                ]);
//
//                $user = DB::table('users')->where('google_id',$google_id)->first();
//
//                Session::put('auth', $user->id);
//
//                return redirect('/');
//
//            }
//            //既に一致するユーザーがいる場合
//            else {
//
//                Session::put('auth', $user->id);
//
//                return redirect('/');
//
//            }
//
//        } catch (\Exception $e) {
////            var_dump($e);
////            throw $e;
//            //todo エラー処理 HTTPステータスコード
////            http_response_code (401);
//            return "失敗";
//        }

    }

}

