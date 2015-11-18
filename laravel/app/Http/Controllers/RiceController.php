<?php

namespace App\Http\Controllers;

use App\Service\RiceService;
use Socialite;
use Session;
use Request;
use DB;

class RiceController extends Controller
{
    use RestControllerTrait;

    // /rice/apply
    protected function receive(RiceService $riceService)
    {
        //本来はsessionを使用。作業用にinputからの値を使用できるようにしている
        if (Request::input('id')) {
            $id = Request::input('id');
        } else {
            $id = Session::get('auth');
        };

        $params = [
            'id' => $id,
            'rice' => Request::input('rice'),
        ];

        //正しく入力されていない場合はエラーを返す
        if (!($params['id'] && $params['rice'])) {
            return $this->responseBad([
                "message" => "入力値が正しくありません。",
            ]);
        }

        //限界値チェック
        $is_limit = $riceService->limitCheck($params);

        //申込可能値を超えていた場合はエラーを返す
        if ($is_limit) {
            return $this->responseBad([
                "message" => "本日申し込み可能な量を超えています。",
            ], 403);
        }

        //申し込む
        $riceService->apply($params);
        return $this->responseOk([]);
    }


    // /rice/today
    protected function today(RiceService $riceService)
    {
        $data = $riceService->today();

        return $this->responseOk([
            "winner"            => $data['winner'],
            "subscriber"        => $data['subscriber'],
            "is_in_apply_time"  => $data['is_in_apply_time'],
            "is_in_result_time" => $data['is_in_result_time']
        ]);
    }
}

