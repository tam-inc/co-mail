<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class SelectWinner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'select:winner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'decide todays winner' ;

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function fire(){

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

        var_dump($winner);

    }
}
