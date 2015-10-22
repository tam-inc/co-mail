<?php
namespace App\Service;

use DB;

/**
 * Created by PhpStorm.
 * User: satake
 * Date: 2015/10/22
 * Time: 14:28
 */


class RiceService{

    public function createApplyTable($params){

        $input_time = \Carbon\Carbon::now()->toDateTimeString();

        DB::table('rice')->insert([
            'user_id' => $params['id'],
            'date' => \Carbon\Carbon::now(),
            'volume' => $params['rice'],
            'winner' => 0,
            'created_at' => $input_time,
            'updated_at' => $input_time,
        ]);

        return;

    }

    public function today(){

        $today = array();
        $today['now'] = \Carbon\Carbon::now();
        $today['date'] = $today['now']->format('Y-m-d');

        //本日の申込者を取ってくる
        $subscriber = DB::table('rice')
            ->leftJoin('users', 'rice.user_id', '=', 'users.id')
            ->where('date',$today['date'])
            ->select('user_id', 'name', 'volume')
            ->get();

        //受付中&結果発表前かどうか
        if($today['now']->hour > 11){
            $is_open = false;
            $limit = true;
        } else if($today['now']->hour > 8){
            $is_open = true;
            $limit = false;
        }

        return json_encode([
            "status" => "OK",
            "is_open" => $is_open,
            "limit" => $limit,
            "subscriber" => $subscriber,
        ]);

    }

}

