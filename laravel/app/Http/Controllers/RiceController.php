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
    protected function recept( RiceService $riceService )
    {
        
        //本来はsessionを使用。作業用にinputからの値を使用できるようにしている
        if( Request::input( 'id' ) ){

            $id = Request::input( 'id' );

        } else {

            $id = Session::get( 'auth' );

        };

        $params = [
            'id'   => $id,
            'rice' => Request::input( 'rice' ),
        ];

        if( $params[ 'id' ] && $params[ 'rice' ] ){

            //限界値チェック
            $is_limit = $riceService->limitCheck( $params , $riceService );

            if($is_limit){

                return $this->responseBad([

                    "message" => "本日申し込み可能な量を超えています。",

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

        //todo trait使用
        return $result;

    }

}

