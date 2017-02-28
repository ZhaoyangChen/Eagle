<?php

namespace App\Console\Commands;

use App\Eagle_keyword;
use App\Http\Controllers\CityListController;
use Illuminate\Console\Command;

class GetKeywordsInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eagle:eat';

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

        $cities = CityListController::citiesArray();
        foreach ($cities as $cityEnglishName => $cityName) {
            $nextCity = false;
            // 断点
//            if ($cityEnglishName == 'beijing') {
//                $page = 20;
//            } else {
//                $page = 1;
//            }
            for(; $page < 500; $page++) {
                var_dump("{$cityName}第{$page}页");
                $url = "http://zhanzhang.baidu.com/keywords/keywordlist?site=http://{$cityEnglishName}.baixing.com/&range=yesterday&page={$page}&pagesize=100";
                $res = self::stable_touch($url, $context);
                if ($res) {
                    $res = json_decode($res);
                    foreach ($res->list as $keyword) {
                        if (intval($keyword->total_click) <= 1) {
                            $nextCity = true;
                            break;
                        }
                        var_dump("  词条 {$keyword->query}");
                        $id = $cityEnglishName . '-' . date('Y-m-d', strtotime('-1 day')) . '-' . $keyword->query;

                        // 详细URL
                        $url2 = "http://zhanzhang.baidu.com/keywords/pagelist?site=http://{$cityEnglishName}.baixing.com/&range=yesterday&page={$page}&pagesize=100&". urlencode("keyword={$keyword->query}");
                        $urlRes = self::stable_touch($url2, $context);
                        $urlRes = json_decode($urlRes);
                        if (!$urlRes) {
                            continue;
                        }
                        $node = new Eagle_keyword();
                        $node->_id = $id;
                        $node->city = $cityEnglishName;
                        $node->date = date('Y-m-d', strtotime('-1 day'));
                        $node->word = $keyword->query;
                        $node->total_display = $keyword->total_display;
                        $node->total_click = $keyword->total_click;
                        $node->total_rank = $keyword->total_rank;
                        $node->average_rank = $keyword->average_rank;
                        $node->detail = $urlRes->list;
                        try {
                            $node->save();
                        } catch(\Exception $e) {
                            $msg = $e->getMessage();
                            var_dump("  捕捉到异常 {$msg}");
                        }

                    }
                }
                if ($nextCity) {
                    break;
                }
            }
        }
    }

    protected static function stable_touch($target, $context, $times = 1) {
        if ($times > 3) {
            \Log::info('http请求失败, 重试超过三次, ' . $target);
            return false;
        }
        try {
            sleep(1);
            return file_get_contents($target, false, $context);
        } catch (\Exception $e) {
            self::stable_touch($target, $context, $times + 1);
        }
    }
}
