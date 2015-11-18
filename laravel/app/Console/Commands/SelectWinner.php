<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Service\RiceService;
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
    protected $description = 'select todays winner' ;

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle( RiceService $riceService )
    {
        $riceService->selectWinner();
    }
}
