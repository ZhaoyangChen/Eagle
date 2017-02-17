<?php

namespace App\Console\Commands;

use App\Eagle_city;
use Illuminate\Console\Command;


class GetCitiesInfo extends Command
{
    const COOKIE_HEADER = "Cookie:__cas__st__=NLI; __cas__id__=0; BAIDUID=B3A216F036F0D0741F7C1BB5B1D337E2:FG=1; PSTM=1485223975; BIDUPSID=D41103B9B46FC9FECF50266B5BEF288D; user-setting-click-times=0%2C1; BDRCVFR[feWj1Vr5u3D]=mk3SLVN4HKm; plus_cv=1::m:f67e827c; H_WISE_SIDS=114454_102567_108270_100039_114144_102523_102629_114311_110003_114000_107917_112106_107317_112134_114130_114512_114329_114535_114314_114447_114154_114275_110085_110444; BDUSS=VxclcyM25OZHhpTjUtaXgyYTAyNlJQYWVPS3UwZ1dRanNMZlhCelRFfk9VY3RZSVFBQUFBJCQAAAAAAAAAAAEAAADk5ngRv83G67yvsNnQ1c34AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAM7Eo1jOxKNYMz; SFSSID=pdgritmk9nb4vqkun06g3us6a4; pgv_pvi=9406346240; pgv_si=s1427085312; BDRCVFR[S4-dAuiWMmn]=I67x6TjHwwYf0; PSINO=5; H_PS_PSSID=1432_19033_21099_21942_17001_22036_20927; SITEMAPSESSID=90uf26ick66n7lbgdib4qdjef6; lastIdentity=PassUserIdentity; Hm_lvt_031f28a54d603d47eb69f579fb3198a0=1484896588,1486346398,1486346452,1486431604; Hm_lpvt_031f28a54d603d47eb69f579fb3198a0=1487301668";

    private $cities;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eagle:hunt';

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

        $this->cities = [
            'beijing',
            'guangzhou',
            'shanghai',
            'tianjin',
            'chongqing',
            'shenyang',
            'nanjing',
            'wuhan',
            'chengdu',
            'xian',
            'jixi',
            'qitaihe',
            'hegang',
            'shuangyashan',
            'taian',
            'chuzhou',
            'laibin',
            'meishan',
            'longnan',
            'wuwei',
            'jiayuguan',
            'hezhou',
            'chongzuo',
            'yichun',
            'yili',
            'ziyang',
            'zhongwei',
            'sanya',
            'xiantao',
            'tianmen',
            'qianjiang',
            'danzhou',
            'dongfang',
            'wenchang',
            'qionghai',
            'wuzhishan',
            'wanning',
            'yl',
            'handan',
            'shijiazhuang',
            'baoding',
            'zhangjiakou',
            'chengde',
            'tangshan',
            'langfang',
            'cangzhou',
            'hengshui',
            'xingtai',
            'qinhuangdao',
            'shuozhou',
            'xinzhou',
            'taiyuan',
            'datong',
            'yangquan',
            'jinzhong',
            'changzhi',
            'jincheng',
            'linfen',
            'lvliang',
            'yuncheng',
            'shangqiu',
            'zhengzhou',
            'anyang',
            'xinxiang',
            'xuchang',
            'pingdingshan',
            'xinyang',
            'nanyang',
            'kaifeng',
            'luoyang',
            'jiaozuo',
            'hebi',
            'puyang',
            'zhoukou',
            'luohe',
            'zhumadian',
            'sanmenxia',
            'jiyuan',
            'tieling',
            'dalian',
            'anshan',
            'fushun',
            'benxi',
            'dandong',
            'jinzhou',
            'yingkou',
            'fuxin',
            'liaoyang',
            'chaoyang',
            'panjin',
            'huludao',
            'changchun',
            'jilin',
            'yanbian',
            'siping',
            'tonghua',
            'baicheng',
            'liaoyuan',
            'songyuan',
            'baishan',
            'haerbin',
            'qiqihaer',
            'mudanjiang',
            'jiamusi',
            'heihe',
            'suihua',
            'daqing',
            'hulunbeier',
            'huhehaote',
            'baotou',
            'wuhai',
            'wulanchabu',
            'tongliao',
            'chifeng',
            'eerduosi',
            'bayannaoer',
            'xilinguole',
            'xingan',
            'alashan',
            'wuxi',
            'zhenjiang',
            'suzhou',
            'nantong',
            'yangzhou',
            'yancheng',
            'xuzhou',
            'huaian',
            'lianyungang',
            'changzhou',
            'tz',
            'suqian',
            'heze',
            'jinan',
            'qingdao',
            'zibo',
            'dezhou',
            'yantai',
            'weifang',
            'jining',
            'weihai',
            'linyi',
            'binzhou',
            'dongying',
            'fuyang',
            'hefei',
            'bengbu',
            'wuhu',
            'huainan',
            'maanshan',
            'anqing',
            'sz',
            'bozhou',
            'huangshan',
            'huaibei',
            'tongling',
            'xuancheng',
            'luan',
            'chaohu',
            'chizhou',
            'quzhou',
            'hangzhou',
            'huzhou',
            'jiaxing',
            'ningbo',
            'shaoxing',
            'taizhou',
            'wenzhou',
            'lishui',
            'jinhua',
            'zhoushan',
            'fuzhou',
            'xiamen',
            'ningde',
            'putian',
            'quanzhou',
            'zhangzhou',
            'longyan',
            'sanming',
            'nanping',
            'zaozhuang',
            'rizhao',
            'laiwu',
            'liaocheng',
            'shanwei',
            'yangjiang',
            'jieyang',
            'maoming',
            'xishuangbanna',
            'dehong',
            'yingtan',
            'xiangfan',
            'ezhou',
            'xiaogan',
            'huanggang',
            'huangshi',
            'xianning',
            'jingzhou',
            'yichang',
            'shiyan',
            'suizhou',
            'jingmen',
            'enshi',
            'shennongjia',
            'yueyang',
            'changsha',
            'xiangtan',
            'zhuzhou',
            'hengyang',
            'chenzhou',
            'changde',
            'yiyang',
            'loudi',
            'shaoyang',
            'xiangxi',
            'zhangjiajie',
            'huaihua',
            'yongzhou',
            'jiangmen',
            'shaoguan',
            'huizhou',
            'meizhou',
            'shantou',
            'shenzhen',
            'zhuhai',
            'foshan',
            'zhaoqing',
            'zhanjiang',
            'zhongshan',
            'heyuan',
            'qingyuan',
            'yunfu',
            'chaozhou',
            'dongguan',
            'fangchenggang',
            'nanning',
            'liuzhou',
            'guilin',
            'wuzhou',
            'guigang',
            'bose',
            'qinzhou',
            'hechi',
            'beihai',
            'xinyu',
            'nanchang',
            'jiujiang',
            'shangrao',
            'fz',
            'yc',
            'jian',
            'ganzhou',
            'jingdezhen',
            'pingxiang',
            'panzhihua',
            'zigong',
            'mianyang',
            'nanchong',
            'dazhou',
            'suining',
            'guangan',
            'bazhong',
            'luzhou',
            'yibin',
            'neijiang',
            'leshan',
            'liangshan',
            'yaan',
            'ganzi',
            'aba',
            'deyang',
            'guangyuan',
            'guiyang',
            'zunyi',
            'anshun',
            'qiannan',
            'qiandongnan',
            'tongren',
            'bijie',
            'liupanshui',
            'qianxinan',
            'zhaotong',
            'kunming',
            'dali',
            'honghe',
            'qujing',
            'baoshan',
            'wenshan',
            'yuxi',
            'chuxiong',
            'puer',
            'lincang',
            'nujiang',
            'diqing',
            'lijiang',
            'lasa',
            'rikaze',
            'shannan',
            'linzhi',
            'changdu',
            'naqu',
            'ali',
            'haikou',
            'tacheng',
            'hami',
            'hetian',
            'aletai',
            'boertala',
            'xianyang',
            'yanan',
            'yulin',
            'weinan',
            'shangluo',
            'ankang',
            'hanzhong',
            'baoji',
            'tongchuan',
            'linxia',
            'lanzhou',
            'dingxi',
            'pingliang',
            'qingyang',
            'jinchang',
            'zhangye',
            'jiuquan',
            'tianshui',
            'gannan',
            'baiyin',
            'yinchuan',
            'shizuishan',
            'wuzhong',
            'guyuan',
            'haibei',
            'xining',
            'haidong',
            'huangnan',
            'guoluo',
            'yushu',
            'haixi',
            'hainan',
            'kelamayi',
            'wulumuqi',
            'shihezi',
            'changji',
            'tulufan',
            'bayinguoleng',
            'akesu',
            'kashi',
            'kunshan',
            'changshu',
            'zhangjiagang',
            'taicang',
            'daxinganling',
            'kezilesu',
            'alaer',
            'wujiaqu',
            'tumushuke',
            'baisha',
            'sansha',
            'baoting',
            'changjiang',
            'chengmai',
            'dingan',
            'ledong',
            'lingao',
            'lingshui',
            'qiongzhong',
            'tunchang',
            'beikezhan',
        ];
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
                'header'=>"Accept-language: en\r\n" . self::COOKIE_HEADER
            )
        );

        $context = stream_context_create($opts);
        foreach ($this->cities as $city) {
            $target = "http://zhanzhang.baidu.com/keywords/index?site=http://{$city}.baixing.com/";
            var_dump($city);
            $html = self::stable_touch($target, $context);
            if (!$html) {
                continue;
            }

            $numberNodes = $html->find('.key-number');

            if (count($numberNodes) !== 2) {
                \Log::info('目标数目异常, 可能访问到异常页面, 或页面DOM结构改变');
                continue;
            }

            $obj = new Eagle_city();
            $obj->_id = $city . '-' . date('Y-m-d', strtotime('-1 day'));
            $obj->city = $city;
            $obj->totalClick = intval(str_replace(',', '', $numberNodes[0]->plaintext));
            $obj->totalDisplay = intval(str_replace(',', '', $numberNodes[1]->plaintext));
            $obj->save();
        }
    }

    protected static function stable_touch($target, $context, $times = 1) {
        if ($times > 3) {
            \Log::info('http请求失败, 重试超过三次, ' . $target);
            return false;
        }
        try {
            sleep(1);
            return file_get_html($target, false, $context);
        } catch (\Exception $e) {
           self::stable_touch($target, $context, $times + 1);
        }
    }
}
