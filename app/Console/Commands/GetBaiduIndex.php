<?php

namespace App\Console\Commands;

use App\Eagle_index;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class GetBaiduIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eagle:getBaiduIndex';

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
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Accept-language: en\r\n" . GetCitiesInfo::COOKIE_HEADER
            )
        );

        $context = stream_context_create($opts);

        $file = fopen(storage_path() . '/app/baiduIndexResource.csv', 'r');
        while($data = fgetcsv($file)) {
            try {
                $domain = $data[1];
                $rule = $data[2];
                $url = $data [3];
                $res = GetKeywordsInfo::stable_touch($url, $context);
                $res_obj = json_decode($res);
                if ($res_obj) {
                    $node = new Eagle_index();
                    $node->_id = $domain . '-' . $rule;
                    $node->domain = $domain;
                    $node->rule = $rule;
                    $node->indexs = [];
                    foreach ($res_obj->list_new as $arr) {
                        $node->indexs[$arr['ctime']] = intval($arr['total']);
                    }
                    $node->updatedTime = time();
                    $former = Eagle_index::find($node->_id);
                    if ($former) {
                        $former->delete();
                    }
                    $node->save();
                }
            } catch (\Exception $e) {
                continue;
            }
        }
    }
}
