<?php

namespace App\Console\Commands;

use App\Eagle_keyword;
use App\Eagle_url;
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
        $multi = 10;

        $cities = CityListController::citiesArray();
        // 断点
//        $cities = array_slice($cities, 203);
        foreach ($cities as $cityEnglishName => $cityName) {
            $nextCity = false;
            for($page = 1; $page < 500; $page++) {
                echo ("{$cityName}第{$page}页\n");
                $url = "http://zhanzhang.baidu.com/keywords/keywordlist?site=http://{$cityEnglishName}.baixing.com/&range=yesterday&page={$page}&pagesize=100";
                $res = self::stable_touch($url, $context);
                if ($res) {
                    $res = json_decode($res);
                    $count = count($res->list);
                    if (!($res->list)) {
                        break;
                    }
                    if($res->list[0]->total_click < 1) {
                        $nextCity = true;
                        break;
                    }
                    for($i = 0; $i < ceil($count / $multi); $i++) {
                        $cluster = array_slice($res->list, $i * $multi, $multi);
                        $urls = [];
                        foreach ($cluster as $keyword) {
                            $cluster[$keyword->query] = $keyword;
                            $urls[$keyword->query] = "http://zhanzhang.baidu.com/keywords/pagelist?site=http://{$cityEnglishName}.baixing.com/&range=yesterday&keyword={$keyword->query}";
                        }
                        $paraRes = self::cmi($urls);
                        echo("当前并发10条HTTP请求\n");
                        foreach ($paraRes as $word => $detail) {
                            $detail = json_decode($detail);
                            if ($detail) {
                                self::insert($cityEnglishName, $cluster[$word], $detail);
                            }
                        }
                    }
                } else {
                    break;
                }
            }
            if ($nextCity) {
                echo("发现点击量极低词汇, 放弃, 前往下一城市\n");
                continue;
            }
        }
    }

    protected static function insert($cityEnglishName, $keyword, $urlRes) {
        $id = $cityEnglishName . '-' . date('Y-m-d', strtotime('-1 day')) . '-' . $keyword->query;
        $node = new Eagle_keyword();
        $node->_id = $id;
        $node->city = $cityEnglishName;
        $node->date = date('Y-m-d', strtotime('-1 day'));
        $node->word = $keyword->query;
        $node->total_display = floatval($keyword->total_display);
        $node->total_click = floatval($keyword->total_click);
        $node->total_rank = floatval($keyword->total_rank);
        $node->average_rank = floatval($keyword->average_rank);
        $node->detail = $urlRes->list;
        try {
            self::urlInsert(json_decode($urlRes->list), $cityEnglishName, $keyword);
            $node->save();
        } catch(\Exception $e) {
            $msg = $e->getMessage();
            var_dump("  捕捉到异常 {$msg}");
        }
    }

    protected static function urlInsert(array $list, $city, $keyword) {
        if (!empty($list)) {
            foreach ($list as $l) {
                $node = new Eagle_url();
                $node->_id = $keyword->query . $l->url;
                $node->city = $city;
                $node->date = date('Y-m-d', strtotime('-1 day'));
                $node->word = $keyword->query;
                $node->total_display = floatval($l->total_display);
                $node->total_click = floatval($l->total_click);
                $node->total_rank = floatval($l->total_rank);
                $node->average_rank = floatval($l->average_rank);
                $node->click_rate = floatval($l->click_rate);
                $node->save();
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

    protected function cmi($connomains,$header = ['header'=>"Accept-language: en\r\n" . GetCitiesInfo::COOKIE_HEADER],$killspace=TRUE,$forhtml=TRUE,$timeout=6, $follow=1){
        $res=array();//用于保存结果
        //$connomains=array_flip(array_flip($connomains));//去除url中的重复项
        $mh = curl_multi_init();//创建多curl对象，为了几乎同时执行
        foreach ($connomains as $i => $url) {
            $conn[$url]=curl_init($url);//若url中含有gb2312汉字，例如FTP时，要在传入url的时候处理一下，这里不用
            curl_setopt($conn[$url], CURLOPT_TIMEOUT, $timeout);//此时间须根据页面的HTML源码出来的时间，一般是在1s内的，慢的话应该也不会6秒，极慢则是在16秒内
            curl_setopt($conn[$url], CURLOPT_HTTPHEADER, $header);//不返回请求头，只要源码
            curl_setopt($conn[$url],CURLOPT_RETURNTRANSFER,1);//必须为1
            curl_setopt($conn[$url], CURLOPT_FOLLOWLOCATION, $follow);//如果页面含有自动跳转的代码如301或者302HTTP时，自动拿转向的页面
            curl_multi_add_handle ($mh,$conn[$url]);//关键，一定要放在上面几句之下，将单curl对象赋给多对象
        }
        //下面一大步的目的是为了减少cpu的无谓负担，暂时不明，来自php.net的建议，几乎是固定用法
        do {
            $mrc = curl_multi_exec($mh,$active);//当无数据时或请求暂停时，active=true
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);//当正在接受数据时
        while ($active and $mrc == CURLM_OK) {//当无数据时或请求暂停时，active=true,为了减少cpu的无谓负担,这一步很难明啊
            if (curl_multi_select($mh) != -1) {
                do {
                    $mrc = curl_multi_exec($mh, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////////
        //下面返回结果
        foreach ($connomains as $i => $url) {
            $cinfo=curl_getinfo($conn[$url]);//可用于取得一些有用的参数，可以认为是header
            //$url=$cinfo[url];//真实url,有些url
            if($killspace){//有点水消耗
                $str=trim(curl_multi_getcontent($conn[$url]));
                $str = preg_replace('/\s(?=\s)/', '', $str);//去掉跟随别的挤在一块的空白
                $str = preg_replace('/[\n\r\t]/', ' ', $str);  //最后，去掉非space 的空白，用一个空格代替
                $res[$i]=stripslashes($str);//取得对象源码，并取消换行，节约内存的同时，可以方便作正则处理
            }else{
                $res[$i]=curl_multi_getcontent($conn[$url]);
            }
            if(!$forhtml){//节约内存
                $res[$i]=NULL;
            }
            /*下面这一段放一些高消耗的程序代码，用来处理HTML，我保留的一句=NULL是要提醒，及时清空对象释放内存，此程序在并发过程中如果源码太大，内在消耗严重
			//事实上，这里应该做一个callback函数或者你应该将你的逻辑直接放到这里来，我为了程序可重复，没这么做
			  preg_match_all($preg,$res[$i],$matchlinks);
			  $res[$i]=NULL;
			*/
            curl_close($conn[$url]);//关闭所有对象
            curl_multi_remove_handle($mh  , $conn[$url]);   //用完马上释放资源

        }
        curl_multi_close($mh);$mh=NULL;$conn=NULL;$connomains=NULL;

        return $res;
    }//cmi
}
