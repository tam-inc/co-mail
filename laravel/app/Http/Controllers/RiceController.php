<?php

namespace App\Http\Controllers;

use App\Service\RiceService;
use App\Service\UserService;
use Socialite;
use Session;
use Request;
use DB;

class RiceController extends Controller
{

    use RestControllerTrait;

    // /rice/apply
    protected function recept( RiceService $riceService )
    {

        $params = [
//            'id' => Session::get( 'auth' ),
            'id'   => Request::input( 'id' ),
            'rice' => Request::input( 'rice' ),
        ];

        if( $params[ 'id' ] && $params[ 'rice' ] ){

            //限界値チェック
            $is_limit = $this->limitCheck( $params , $riceService );

            if($is_limit){

                return $this->responseBad([

                    "message" => "本日申し込める量を超えています。"

                ]);

            }

            //申し込む
            $riceService->apply( $params );

            return $this->responseOk([]);

        } else {

            return $this->responseBad([

                "message" => "入力値が正しくありません。",

            ]);

        }

    }


    // /rice/today
    protected function today( RiceService $riceService )
    {

        $result = $riceService->today();

        return $result;

    }


    protected function limitCheck( $params , $riceService ){

        $is_limit = $riceService->limitCheck( $params );

        return $is_limit;

    }


    protected function getTodayWinner( RiceService $riceService )
    {

        $result = $riceService->getTodayWinner();

        return [$result];

    }

}

