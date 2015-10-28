<?php
namespace App\Service;

use DB;
use Illuminate\Http\JsonResponse;

/**
 * Created by PhpStorm.
 * User: satake
 * Date: 2015/10/22
 * Time: 14:28
 */

class RiceService
{

    //申し込む
    public function apply($params)
    {

        $today = [
            'now' => \Carbon\Carbon::now(),
            'y-m-d' =>  \Carbon\Carbon::now()->format('Y-m-d'),
        ];

        //限界値以上申し込まれているかチェック
        $limit_volume = 10;
        $today_volume = (int)$this->getTotalVolume();
        $today_residual_quantity = $limit_volume - $today_volume;

        //updateする量が限界値以上かチェック
        //todo 既に申し込みの場合は残量が変わるのでロジック見直し
        if($today_residual_quantity >= $params['rice']){

            $is_limit = false;

        } else {

            $is_limit = true;

        }

        if($is_limit){

            return JsonResponse::create([
                "status" => "BAD",
                "residual_quantity" => $today_residual_quantity,
                "message" => "本日の申し込める量を超えています。",
            ],400);

        }

        //既に申し込み済みかチェック
        $applied = DB::table('rice')
            ->where('date', $today['now'])
            ->where('user_id',$params['id'])
            ->get();

        if($applied){

            DB::table('rice')
                ->where('date', $today['now'])
                ->where('user_id',$params['id'])
                ->update([
                    'volume' => $params['rice'],
                ]);

        } else {

            DB::table('rice')->insert([
                'user_id' => $params['id'],
                'date' => $today['now'],
                'volume' => $params['rice'],
                'winner' => 0,
                'created_at' => $today['now']->toDateTimeString(),
                'updated_at' => $today['now']->toDateTimeString(),
            ]);

        }

        return JsonResponse::create([
            "status" => "OK",
        ],200);

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

    //本日の分量を取ってくる
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