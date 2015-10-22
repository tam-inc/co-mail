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
}

