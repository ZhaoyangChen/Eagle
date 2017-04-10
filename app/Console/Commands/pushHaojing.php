<?php

namespace App\Console\Commands;

use App\Eagle_url;
use App\Http\Controllers\CityListController;
use Illuminate\Console\Command;

class pushHaojing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eagle:haojing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    const HAOJING_URL = 'http://www.baixing.com/mkt/eaglelink';
    const EAGLE_SECRET = 'baixing_eagle';

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
        $cities = CityListController::citiesArray();
        $categories = self::getCategories();

        $ch = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, self::HAOJING_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        foreach ($cities as $cityId => $cityName) {
            foreach ($categories as $categoryId) {
                var_dump($cityId . '-' .$categoryId);
                $res = Eagle_url::where('city', $cityId)->where('category', $categoryId)->whereBetween('average_rank', [5, 50])
                    ->orderBy('total_click', 'desc')
                    ->take(20)
                    ->get();
                $link = [];
                if ($res) {
                    foreach ($res as $r) {
                        if (strpos('.html', $r->url) !== false) {
                            continue;
                        }
                        $link[$r->url] = $r->word;
                    }
                }
                var_dump($link);
                $params = [
                    'category'  =>  $categoryId,
                    'city'      =>  $cityId,
                    'link'      =>  serialize($link)
                ];
                $params['sign'] = md5(implode('', $params) . self::EAGLE_SECRET);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                $response = curl_exec($ch);//接收返回信息
                if(curl_errno($ch)){//出错则显示错误信息
                    echo curl_error($ch);
                }
                var_dump($response);
            }

            sleep(1);
        }
        curl_close($ch); //关闭curl链接
    }

    public static function getCategories() {
        return [
            'ershou',
            'chongwuleimu',
            'cheliang',
            'huodong',
            'fang',
            'jianzhi',
            'jiaoyupeixun',
            'gongzuo',
            'qiuzhi',
            'fuwu',
            'zengsong',
            'shouji',
            'diannao',
            'bijiben',
            'pingbandiannao',
            'shumachanpin',
            'yinger',
            'dianqi',
            'jiaju',
            'fushi',
            'menpiao',
            'zhaoxiangji',
            'shoujipeijian',
            'riyongpin',
            'yundongqicai',
            'nongchanpin',
            'yueqi',
            'bangongyongpin',
            'bangongjiaju',
            'shoucang',
            'qishipenjing',
            'xuniwupin',
            'qitazhuanrang',
            'chongwujiaoyi',
            'chongwumao',
            'qitachongwu',
            'chongwulingyang',
            'chongwuyongpin',
            'chongwupeizhong',
            'zhaochongwu',
            'ershouqiche',
            'ershougongchengche',
            'gongchengche',
            'ershoumotuoche',
            'ershoukache',
            'tuolaji',
            'ershoudiandongche',
            'ershouzixingche',
            'daikuangouche',
            'xincheyouhui',
            'xiaxianche',
            'qichebaoyang',
            'pinchesfc',
            'qicheyongpin',
            'cheyongpeijian',
            'shangpaiyanche',
            'cheliangqiugou',
            'shiguchejiqita',
            'zhenghun',
            'nanzhaonv',
            'juhui',
            'jinengjiaohuan',
            'hunjie',
            'xunrenqishi',
            'baobeixunjia',
            'jiaoyouqun',
            'qitajiaoyou',
            'zhengzu',
            'duanzu',
            'ershoufang',
            'xinfangchushou',
            'jingyingzhuanrang',
            'shangpuchushou',
            'shangpuzhuanrang',
            'shangpu',
            'shoufang',
            'changfang',
            'qiufang',
            'zhaoshiyou',
            'xiaoqu',
            'cuxiaojianzhi',
            'paifa',
            'xueshengjianzhi',
            'wangluojianzhi',
            'jiajiao',
            'caiyilaoshi',
            'chongchang',
            'zhanhuijianzhi',
            'cantingjianzhi',
            'kefujianzhi',
            'mote',
            'yanyuan',
            'shejijianzhi',
            'wangzhan',
            'wenjuanjianzhi',
            'kuaijijianzhi',
            'sheyingjianzhi',
            'fanyijianzhi',
            'qun',
            'qitajianzhi',
            'zxxjiaoyu',
            'jinengpeixun',
            'youerpeixun',
            'jiajiaojiaoyu',
            'xuelipeixun',
            'waiyupeixun',
            'wentipeixun',
            'diannaopeixun',
            'shejipeixun',
            'tiyupeixun',
            'gongren',
            'siji',
            'chushi',
            'baoan',
            'xiaoshou',
            'fangdichan',
            'renshi',
            'wenmi',
            'fuwuyuan',
            'songhuoyuan',
            'dianyuan',
            'kefu',
            'yisheng',
            'meigong',
            'fanyi',
            'kuaiji',
            'jixie',
            'qichemeirong',
            'caigou',
            'jinrong',
            'baoxianzhaopin',
            'chengxuyuan',
            'meirongshi',
            'laoshi',
            'shichang',
            'bangyong',
            'taobaojob',
            'daoyou',
            'jianshen',
            'falv',
            'baojian',
            'yinshiyule',
            'dianzi',
            'nonglinmuyu',
            'zhaopinhui',
            'chuguolaowu',
            'ktvjiuba',
            'qitazhaopin',
            'jinrongfuwu',
            'jiameng',
            'jiaxiaofuwu',
            'gerenzuche',
            'banjia',
            'jiadianweixiu',
            'kaisuo',
            'shumaweixiu',
            'wupinhuishou',
            'yule',
            'yundongjianshen',
            'licaifuwu',
            'lvshifuwu',
            'yangshengbaojian',
            'peijiafuwu',
            'peijiapeilian',
            'baoxianfuwu',
            'daibanzhuce',
            'kuaijifuwu',
            'siyi',
            'sheyingfuwu',
            'meirongfuwu',
            'zhuangxiu',
            'jiatingzhuangxiu',
            'chaijiu',
            'jiancaizhuangshi',
            'ruanzhuang',
            'baomu',
            'baojieqingxi',
            'xiyihuli',
            'diannaoweixiu',
            'wangluobuxian',
            'jianzhuweixiu',
            'bangongweixiu',
            'wupinpifa',
            'shoujiweixiu',
            'weixiu',
            'fangwuweixiu',
            'jiajuweixiu',
            'canyin',
            'canyinmeishi',
            'lvxingshe',
            'jiudianfuwu',
            'jipiaofuwu',
            'qianzhengfuwu',
            'yiminfuwu',
            'gongyeshebei',
            'wangzhanjianshe',
            'daiyunyingtg',
            'zulin',
            'kuaidi',
            'kuaidifuwu',
            'qingdian',
            'zhanlanzhanhui',
            'zixun',
            'yinshuapenghui',
            'penhuizhaopai',
            'sheji',
            'guanggaomeiti',
            'fanyifuwu',
            'suji',
            'lipinfuwu',
            'xianhualipin',
            'bendimingzhan',
            'quanxinshangjia',
            'nongye',
            'binzang',
            'gongyijianding',
            'qitafuwu',
        ];
    }
}
