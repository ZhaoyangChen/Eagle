<?php

namespace App\Console\Commands;

use App\Eagle_keyword;
use Illuminate\Console\Command;

class MinuteTick extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eagle:tick';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dates = ["2017-03-12", "2017-03-11", "2017-03-10", "2017-03-09"];
        $file = fopen(public_path() . "/bingyan.csv","w");
        fputcsv($file, ['keyword', 'city', 'date', 'totalDisplay', 'totalClick', 'totalRank', 'averageRank', 'detail']);

        foreach ($dates as $date) {
            var_dump($date);
            $res = Eagle_keyword::orderBy('average_rank', 'asc')->where("average_rank", '>', 0)->where("date", $date)->take(1000)->get();
            foreach ($res as $r) {
                fputcsv($file, [$r->word, $r->city, $r->date, $r->total_display, $r->total_click, $r->total_rank, $r->average_rank, json_encode($r->detail)]);
            }
        }
        fclose($file);
    }
}
