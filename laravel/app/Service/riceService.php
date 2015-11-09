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

    public function limitCheck( $params )
    {

        //todo 限界値が決め打ちになっているのでどこかに変数で持つ
        $limit_volume      = ( float )10;

        $today_volume      = ( float )$this->getTotalVolumeNotSelf( $params );

        $residual_quantity = $limit_volume - $today_volume;

        //updateする量が申込可能値以上かチェック
        if( $residual_quantity >= $params[ 'rice' ] ){

            $is_limit = false;

        } else {

            $is_limit = true;

        }

        return $is_limit;

    }


    //申し込む
    public function apply( $params )
    {

        try {

            DB::beginTransaction();

                $this->deleteApplyData( $params );

                $this->insertApplyData( $params );

            DB::commit();

        } catch ( \Exception $e ){

            DB::rollback();

        };

    }

    //申込内容を削除
    protected function deleteApplyData( $params ){

        $now = \Carbon\Carbon::now();

        DB::table( 'rice' )
            ->where( 'date' , $now )
            ->where( 'user_id' , $params[ 'id' ] )
            ->delete();

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


    //現在の受付状況等をJSONで返す
    public function today()
    {

        return json_encode([
            "status"     => "OK",
            "subscriber" => $this->getSubscriber(),
            "winner"     => $this->getTodayWinner(),
            "is_in_apply_time"  => $this->getInApplyTime(),
            "is_in_result_time" => $this->getInResultTime(),
        ]);

    }


    //申し込み可能時間かどうか
    protected function getInApplyTime(){

        $now = \Carbon\Carbon::now();

        if ( $now->hour > 12 ) {

            $is_open = false;

        } else if( $now->hour > 8 ){

            $is_open = true;

        }

        return $is_open;
    }


    //結果発表中かどうか
    protected function getInResultTime(){

        $now = \Carbon\Carbon::now();

        $winner = $this->getTodayWinner();

        if ( $winner ) {

            return true;

        } else {

            return false;

        }

    }


    //米を炊く人を取得
    protected function getTodayWinner(){

        $now = \Carbon\Carbon::now();

        $winner = DB::table( 'rice' )
            ->leftJoin( 'users' , 'rice.user_id' , '=' , 'users.id' )
            ->where( 'date' , $now->format( 'Y-m-d' ) )
            ->where( 'winner' , '>' ,'0' )
            ->select( 'user_id' , 'name' )
            ->get();

        return $winner;

    }


    //米を炊く人を選出
    public function selectWinner(){

        $now = \Carbon\Carbon::now();

        $winner = $this->getTodayWinner();

        if( !$winner ){

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
                "today"        => $now->format( 'Y-m-d' ),
                "one_week_ago" => $now->subDay( 7 )->format( 'Y-m-d' ),
            ];

            $pickup = DB::select( $sql , $bind );

            //row->countをkeyにして配列を作成する
            $result = [];
            foreach($pickup as $row ){
                $tmp   = array_get( $result , $row->count , [] );
                $tmp[] = $row;
                $result[ $row->count ] = $tmp;
            }

            $min = min( array_keys( $result ) );
            $candidate = $result[ $min ];

            //候補者からランダムに選出
            $result_key = array_rand( $candidate );
            $winner     = $candidate[ $result_key ];

            $this->insertWinner( $winner );

        } else {

            return $winner;

        }

    }


    //本日の米を炊く人をDBに入れる
    protected function insertWinner( $winner ){

        $now = \Carbon\Carbon::now();

        DB::table( 'rice' )
            ->where( 'date' ,  $now->format( 'Y-m-d' ) )
            ->where( 'user_id' , '=' , $winner->user_id )
            ->update( ['winner' => 1] );

    }


    //本日の申込者を取得
    protected function getSubscriber(){

        $now = \Carbon\Carbon::now();

        $result = DB::table( 'rice' )
            ->leftJoin( 'users' , 'rice.user_id' , '=' , 'users.id' )
            ->where( 'date' , $now->format( 'Y-m-d' ) )
            ->select( 'user_id' , 'name' , 'volume' )
            ->get();

        return $result;

    }


    //本日の総申込量を取得
    protected function getTotalVolume(){

        $now = \Carbon\Carbon::now();

        $result = DB::table( 'rice' )
            ->where( 'date' , $now->format( 'Y-m-d' ) )
            ->where( 'volume' , '>' , 0 )
            ->sum( 'volume' );

        return $result;

    }

    //本日の自分以外の総申込量を取得
    public function getTotalVolumeNotSelf( $params ){

        $now = \Carbon\Carbon::now();

        $result = DB::table( 'rice' )
            ->where( 'date' , $now->format( 'Y-m-d' ) )
            ->where( 'volume' , '>' , 0 )
            ->whereNotIn( 'user_id' , [ $params[ 'id' ] ] )
            ->sum( 'volume' );

        return $result;

    }

}