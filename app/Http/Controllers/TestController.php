<?php

namespace App\Http\Controllers;

use App\Eagle_city;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

const COOKIE_HEADER = "Cookie:__cas__st__=NLI; __cas__id__=0; BAIDUID=B3A216F036F0D0741F7C1BB5B1D337E2:FG=1; PSTM=1485223975; BIDUPSID=D41103B9B46FC9FECF50266B5BEF288D; plus_cv=1::m:f67e827c; H_WISE_SIDS=114454_102567_108270_100039_114144_102523_102629_110003_114000_114208_112142_107917_112106_107317_112134_114130_114512_114329_114314_114447_114154_114275_110085_110444; user-setting-click-times=0%2C1; pgv_pvi=9566489600; pgv_si=s4879888384; BDUSS=Q2R0k0Z0lER29wWTNwcnp0dGZ4SS1ucG14TG12OTh4LVZtdXlLVzZWUVpjc0pZSVFBQUFBJCQAAAAAAAAAAAEAAADk5ngRv83G67yvsNnQ1c34AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABnlmlgZ5ZpYTV; BDRCVFR[feWj1Vr5u3D]=I67x6TjHwwYf0; PSINO=5; H_PS_PSSID=1432_19033_21099_21942_17001_22036_22023_20927; SITEMAPSESSID=rtc6vnvfa2u39u6d7rfk39kad4; lastIdentity=PassUserIdentity; Hm_lvt_031f28a54d603d47eb69f579fb3198a0=1484896588,1486346398,1486346452,1486431604; Hm_lpvt_031f28a54d603d47eb69f579fb3198a0=1486624185";

class TestController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function test() {
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Accept-language: en\r\n" . COOKIE_HEADER
            )
        );

        $context = stream_context_create($opts);
        foreach (['beijing'] as $city) {
            $target = "http://zhanzhang.baidu.com/keywords/index?site=http://{$city}.baixing.com/";
            $html = file_get_html($target, false, $context);
            $numberNodes = $html->find('.key-number');

            if (count($numberNodes) !== 2) {
                \Log::info('目标数目异常, 可能访问到异常页面, 或页面DOM结构改变');
                continue;
            }

            $obj = new Eagle_city();
            $obj->id = $city . '-' . date('Y-m-d', strtotime('-1 day'));
            $obj->totalClick = intval(str_replace(',', '', $numberNodes[0]->plaintext));
            $obj->totalDisplay = intval(str_replace(',', '', $numberNodes[1]->plaintext));
            $obj->save();
        }
    }
}
