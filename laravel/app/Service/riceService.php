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
    //本日の申込可能量を超えていないかチェック
    public function limitCheck($params)
    {
        $limit_volume = (float)env('TOKYO_LIMIT');
        $today_volume = (float)$this->getTotalVolumeNotSelf($params);

        //本日の申込可能量
        $acceptable_volume = $limit_volume - $today_volume;

        //updateする量が申込可能量を超えていないか判定
        return !($acceptable_volume >= $params['rice']);
    }


    //申し込む
    public function apply($params)
    {
        try {
            DB::beginTransaction();
            $this->deleteApplyData($params);
            $this->insertApplyData($params);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        };
    }


    //申込内容を削除
    protected function deleteApplyData($params)
    {
        $now = \Carbon\Carbon::now();

        DB::table('rice')
            ->where('date', $now->format('Y-m-d'))
            ->where('user_id', $params['id'])
            ->delete();
    }


    //新規申込
    protected function insertApplyData($params)
    {
        $now = \Carbon\Carbon::now();

        DB::table('rice')->insert([
            'winner'     => 0,
            'date'       => $now->format('Y-m-d'),
            'user_id'    => $params['id'],
            'volume'     => $params['rice'],
            'area'       => 0,
            'created_at' => $now->toDateTimeString(),
            'updated_at' => $now->toDateTimeString(),
        ]);
    }


    //現在の受付状況等をJSONで返す
    public function today()
    {
        //レスポンス用にデータを整形
        $data = $this->formatResponseData();
        
        return [
            "subscriber"        => $data['subscriber'],
            "winner"            => $data['winner'],
            "is_in_apply_time"  => $this->isInApplyTime(),
            "is_in_result_time" => $this->isInResultTime()
        ];
    }


    //レスポンス用にデータを整形
    protected function formatResponseData(){
        //winnerをレスポンス用に整形
        $winner = $this->getTodayWinner();
        if (empty($winner)) {
            //空の配列を空のオブジェクトに変換
            $winner = new \stdClass();
        } else {
            //user_idをidに変換
            $winner = $this->changeUserIDToID($winner)[0];
        };

        //subscriberをレスポンス用に整形
        $subscriber = $this->changeUserIDToID($this->getSubscriber());

        $data = [
            'winner'    => $winner,
            'subscriber'=> $subscriber
        ];

        return $data;
    }


    //申し込み可能時間かどうか
    protected function isInApplyTime()
    {
        $now = \Carbon\Carbon::now();
        $limit_time = $now->copy()->hour(11)->minute(40);
        $apply_start_time = $now->copy()->hour(8);

        if ($now > $limit_time) return false;
        if ($now > $apply_start_time) return true;
        return false;
    }


    //結果発表中かどうか
    protected function isInResultTime()
    {
        $winner = $this->getTodayWinner();
        return !(empty($winner));
    }


    //米を炊く人を取得
    protected function getTodayWinner()
    {
        $now = \Carbon\Carbon::now();

        $winner = DB::table('rice')
            ->leftJoin('users', 'rice.user_id', '=', 'users.id')
            ->where('date', $now->format('Y-m-d'))
            ->where('winner', '>', '0')
            ->select('user_id', 'name')
            ->get();

        return $winner;
    }

    //米を炊く人を選出
    public function selectWinner()
    {
        $now = \Carbon\Carbon::now();

        $winner = $this->getTodayWinner();
        if (!empty($winner)) return $winner;

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
    where date BETWEEN :search_start_day AND :today
    and winner = '1'
    group by user_id
) as count_table ON count_table.user_id = rice.user_id
WHERE date=:today
ORDER BY count,RANDOM()
SQL;

        $bind = [
            "today" => $now->format('Y-m-d'),
            "search_start_day" => $now->subDay(7)->format('Y-m-d'),
        ];

        $pickup = DB::select($sql, $bind);

        $this->insertWinner($pickup[0]);
    }


    //本日の米を炊く人をDBに入れる
    protected function insertWinner($winner)
    {
        $now = \Carbon\Carbon::now();

        DB::table('rice')
            ->where('date', $now->format('Y-m-d'))
            ->where('user_id', '=', $winner->user_id)
            ->update(['winner' => 1]);
    }


    //本日の申込者を取得
    protected function getSubscriber()
    {
        $now = \Carbon\Carbon::now();

        $subscriber = DB::table('rice')
            ->leftJoin('users', 'rice.user_id', '=', 'users.id')
            ->where('date', $now->format('Y-m-d'))
            ->select('user_id', 'name', 'volume')
            ->get();

        return $subscriber;
    }


    //user_idをidに変換する
    protected function changeUserIDToID($data)
    {
        $result = [];

        foreach ($data as $i) {
            $i->id = $i->user_id;
            unset($i->user_id);
            array_push($result, $i);
        }

        return $result;
    }


    //本日の総申込量を取得
    protected function getTotalVolume()
    {
        $now = \Carbon\Carbon::now();

        $result = DB::table('rice')
            ->where('date', $now->format('Y-m-d'))
            ->where('volume', '>', 0)
            ->sum('volume');

        return $result;
    }


    //本日の自分以外の総申込量を取得
    public function getTotalVolumeNotSelf($params)
    {
        $now = \Carbon\Carbon::now();

        $result = DB::table('rice')
            ->where('date', $now->format('Y-m-d'))
            ->where('volume', '>', 0)
            ->whereNotIn('user_id', [$params['id']])
            ->sum('volume');

        return $result;
    }
}
