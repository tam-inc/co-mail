<?php

namespace App\Http\Controllers;

use App\Service\UserService;
use Socialite;
use Session;

class AuthController extends Controller
{

    use RestControllerTrait;

    //ログインチェック
    public function getUser( UserService $userService ){

        $session = Session::get( 'auth' );

        if( $session ){

            $user = $userService -> getUser( $session );

            return $this->responseOk([

                "user" => $user,

            ]);

        } else {

            return $this->responseBad([

                "message" => "ログインしていません。",

            ]);

        }

    }

    //google認証
    public function auth()
    {

        return Socialite::with( 'google' )->redirect();

    }

    //google認証リダイレクトページ
    public function googleCallback( UserService $userService )
    {

        $google_info = Socialite::with( 'google' )->user();

        $google_id = ( int )$google_info->getId();

        $user = $userService -> getUserByGoogleID( $google_id );

        if( !$user ) {

            $userService -> createUser( $google_info , $google_id );

            $user = $userService -> getUserByGoogleID( $google_id );

        }

        Session::put( 'auth' , $user->id );

        return redirect( '/' );

    }

}