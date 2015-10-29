<?php
namespace App\Service;

use DB;

/**
 * Created by PhpStorm.
 * User: satake
 * Date: 2015/10/22
 * Time: 14:28
 */


class UserService{

    public function createUser( $google_info , $google_id )
    {

        $now = \Carbon\Carbon::now();

        DB::table( 'users' )->insert([

            'google_id'  => $google_id,
            'name'       => $google_info->getName(),
            'email'      => $google_info->getEmail(),
            'created_at' => $now->toDateTimeString(),
            'updated_at' => $now->toDateTimeString(),

        ]);

    }

    public function getUser( $id )
    {

        $user = DB::table( 'users' )
            ->where( 'id' , $id )
            ->select( 'id' , 'name' , 'email' )
            ->first();

        return $user;

    }

    public function getUserByGoogleID( $google_id )
    {

        $user = DB::table( 'users' )
            ->where( 'google_id' , $google_id )
            ->select( 'id' )
            ->first();

        return $user;

    }

}

