<?php
namespace App\Service;

use DB;

/**
 * Created by PhpStorm.
 * User: satake
 * Date: 2015/10/22
 * Time: 14:28
 */

class RiceService
{

    //申し込み者をDBに入れる
    public function createApplyTable($params)
    {

        $input_time = \Carbon\Carbon::now()->toDateTimeString();

        DB::table('rice')->insert([
            'user_id' => $params['id'],
            'date' => \Carbon\Carbon::now(),
            'volume' => $params['rice'],
            'winner' => 0,
            'created_at' => $input_time,
            'updated_at' => $input_time
        ]);

    }

    //現在の受付状況等をJSONで返す
    public function today()
    {
        return json_encode([
            "status" => "OK",
            "is_open" => $this->getIsOpen(),
            "winner" => $this->getWinner(),
            "subscriber" => $this->getSubscriber(),
        ]);
    }

    //受付中かどうか
    protected function getIsOpen(){

        $today = [
            'now' => \Carbon\Carbon::now(),
            'y-m-d' =>  \Carbon\Carbon::now()->format('Y-m-d'),
        ];

        if ($today['now']->hour > 11) {
            return $is_open = false;
        } else if($today['now']->hour > 8){
            return $is_open = true;
        }

    }

    //米を炊く人を選出
    protected function getWinner(){

        $today = [
            'now' => \Carbon\Carbon::now(),
            'y-m-d' =>  \Carbon\Carbon::now()->format('Y-m-d'),
            'one_week_ago' => \Carbon\Carbon::now()->subDay(7)->format('Y-m-d'),
        ];

        $sql = <<<SQL
SELECT
	rice.id,
	rice.user_id,
	users.name AS name,
	COALESCE(count_table.count,0) as count
FROM (rice LEFT JOIN users ON rice.user_id = users.id)
LEFT JOIN (
	SELECT
		user_id,
		count(*) as count
	FROM rice
	where date BETWEEN :one_week_ago AND :today
	and winner = '1'
	group by user_id
) as count_table ON count_table.user_id = rice.user_id
WHERE date=:today
SQL;
        $bind = [
            "today" => $today['y-m-d'],
            "one_week_ago" => $today['one_week_ago'],
        ];
        $pickup = DB::select($sql,$bind);


        //row->countをkeyにして配列を作成する
        $result = [];
        foreach($pickup as $row ){
            $tmp = array_get($result,$row->count,[]);
            $tmp[] = $row;
            $result[$row->count] = $tmp;
        }

        $min = min(array_keys($result));
        $candidate = $result[$min];

        //候補者からランダムに選出
        $result_key = array_rand($candidate);
        $winner = $candidate[$result_key];

        return (array)$winner;

    }

    //本日の申込者を取ってくる
    protected function getSubscriber(){

        $today = [
            'now' => \Carbon\Carbon::now(),
            'y-m-d' =>  \Carbon\Carbon::now()->format('Y-m-d'),
        ];

        $result = DB::table('rice')
            ->leftJoin('users', 'rice.user_id', '=', 'users.id')
            ->where('date', $today['y-m-d'])
            ->select('user_id', 'name', 'volume')
            ->get();

        return $result;

    }

    //本日の分量を取ってくる フロントで計算？
    protected function getTotalVolume(){

        $today = [
            'now' => \Carbon\Carbon::now(),
            'y-m-d' =>  \Carbon\Carbon::now()->format('Y-m-d'),
        ];

        $result = DB::table('rice')
            ->where('date', $today['y-m-d'])
            ->where('volume', '>', 0)
            ->sum('volume');

        return $result;

    }

}