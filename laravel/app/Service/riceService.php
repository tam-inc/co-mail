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

    //限界値を超えているかどうか確認
    public function limitCheck( $params )
    {

        $limit_volume      = (float)10;

        $today_volume      = (float)$this->getTotalVolume();

        $applied = $this->appliedCheck( $params );

        //申込可能値設定 新規か変更かどうかで値を分ける
        if( $applied ){

            $residual_quantity = $limit_volume - ( $today_volume - $applied->volume ) ;

        } else {

            $residual_quantity = $limit_volume - $today_volume;

        }

        //updateする量が申込可能値以上かチェック
        if( $residual_quantity >= $params[ 'rice' ] ){

            $is_limit = false;

        } else {

            $is_limit = true;

        }

        return $is_limit;

    }


    //申し込む
    public function apply($params)
    {

        $applied = $this->appliedCheck( $params );

        if( $applied ){

            $this->updateApplyData( $params );

        } else {

            $this->insertApplyData( $params );

        }

    }


    //申込済みかチェック
    protected function appliedCheck( $params ){

        $now = \Carbon\Carbon::now();

        //申込済みかどうかチェック
        $applied_data = DB::table( 'rice' )
            ->where( 'date' , $now )
            ->where( 'user_id' , $params[ 'id' ] )
            ->first();

        return $applied_data;

    }


    //新規申込
    protected function insertApplyData( $params ){

        $now = \Carbon\Carbon::now();

        DB::table( 'rice' )->insert([

            'winner'     => 0,
            'date'       => $now,
            'user_id'    => $params[ 'id' ],
            'volume'     => $params[ 'rice' ],
            'created_at' => $now->toDateTimeString(),
            'updated_at' => $now->toDateTimeString(),

        ]);

    }


    //申込内容変更
    protected function updateApplyData( $params ){

        $now = \Carbon\Carbon::now();

        DB::table( 'rice' )
            ->where( 'date' , $now )
            ->where( 'user_id' ,$params[ 'id' ] )
            ->update(['volume' => $params[ 'rice' ]] );

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

    public function getTodayWinner(){

        $today = [
            'now' => \Carbon\Carbon::now(),
            'y-m-d' =>  \Carbon\Carbon::now()->format('Y-m-d'),
        ];

        $result = DB::table('rice')
            ->where('date', $today['now'])
            ->where('winner','>','0')
            ->get();

        if(empty($result)){

             return false;

        } else {

            return true;

        }

    }

    //米を炊く人を選出
    protected function getWinner(){

        $today = [
            'now' => \Carbon\Carbon::now(),
            'y-m-d' =>  \Carbon\Carbon::now()->format('Y-m-d'),
            'one_week_ago' => \Carbon\Carbon::now()->subDay(7)->format('Y-m-d'),
        ];

        //todo winnerがいたら選出を行わない処理をする

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