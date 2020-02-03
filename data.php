<?php

// 引入数据文件
require 'vendor/autoload.php';
use QL\QueryList;
// 获取文件地址（请给json文件 777 权限）
$json_path = dirname(__FILE__).'/json/';
// 获取文本之间的数据
function get_between($input, $start, $end) {
    $substr = substr($input, strlen($start)+strpos($input, $start),(strlen($input) - strpos($input, $end))*(-1));
    return $substr;
}

try {
    $ql = QueryList::get('https://3g.dxy.cn/newh5/view/pneumonia');
// overall_information
    $text = $ql->find('#getStatisticsService')->text();
    $overall_json = get_between($text,'getStatisticsService = ','}catch');
    $array = json_decode($overall_json,TRUE);
// 获取抓取的时间
    $crawler_time  = $array['modifyTime'];
// province_information
    $text = $ql->find('#getListByCountryTypeService1')->text();
    $province_json = get_between($text,'getListByCountryTypeService1 = ','}catch');
// area_information
    $text = $ql->find('#getAreaStat')->text();
    $area_json = get_between($text,'getAreaStat = ','}catch');
// abroad_information
    $text = $ql->find('#getListByCountryTypeService2')->text();
    $abroad_json = get_between($text,'getListByCountryTypeService2 = ','}catch');
// news
    $text = $ql->find('#getTimelineService')->text();
    $news_json = get_between($text,'getTimelineService = ','}catch');
// 保存
    file_put_contents($json_path.'info.json',$overall_json);
    $path = $json_path.$crawler_time;
// 创建文件夹
    if (is_dir($path)){
        echo date('Y年y月d日H时m分s秒').":对不起！目录 " . $path . " 已经存在！<br/>";
    }else{
        //第三个参数是“true”表示能创建多级目录，iconv防止中文目录乱码
        $res=mkdir(iconv("UTF-8", "GBK", $path),0777,true);
        if ($res){
            echo date('Y年y月d日H时m分s秒').":目录 $path 创建成功";
        }else{
            echo date('Y年y月d日H时m分s秒').":目录 $path 创建失败";
            exit;
        }
        echo "<br/>";
    }

    file_put_contents($path .'/overall.json',$overall_json);
    file_put_contents($path .'/province.json',$province_json);
    file_put_contents($path .'/area.json',$area_json);
    file_put_contents($path .'/abroad.json',$abroad_json);
    file_put_contents($path .'/news.json',$news_json);
    echo date('Y年y月d日H时m分s秒').':抓取成功';

} catch (\Exception $e) {
    echo $e->getMessage();
    die(); // 终止异常
}




