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

        try {

            $user = Socialite::with('google')->user();
            $id = $user->getId();

            //todo model作成
            //DBからメールアドレスが一致したユーザー情報を取得
            $user_exist = DB::table('users')->where('google_id',$id)->first();

            if(!$user_exist) {

                DB::table('users')->insert([
                    'google_id' => $user->getId(),
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
                ]);

                DB::table('rise')->insert([
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'volume' => 0,
                    'apply_date' => \Carbon\Carbon::now()->toDateTimeString(),
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
                ]);

                Session::put('auth', $id);

                return redirect('/');

            } else {

                Session::put('auth', $id);

                return redirect('/');

            }

        } catch (\Exception $e) {
//            var_dump($e);
//            throw $e;
            //todo エラー処理 HTTPステータスコード
            return "失敗";
        }

    }

}

