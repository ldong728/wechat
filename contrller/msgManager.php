<?php

/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/4/17
 * Time: 8:38
 */

$mypath = $_SERVER['DOCUMENT_ROOT'] . '/wechat';
include_once $mypath . '/includes/magicquotes.inc.php';
include_once $mypath . '/includes/db.inc.php';
include_once $mypath . '/includes/xdsmdb.php';
include_once $mypath . '/includes/helpers.inc.php';
include_once $mypath . '/contrller/serveManager.php';
include_once $mypath . '/class/wechat.php';
include_once $mypath . '/class/jokeMaker.php';
include_once $mypath . '/class/mobilePhoneQuery.php';

//wxlog('get msg');
//$weixin = new wechat($weixinId);
//$weixin->valid();
//$msg = $weixin->receiverFilter();
//wxlog('filter return ok content:'. $msg['content']);
//$userId = '';

if ($msg['type'] == 'text') {
    switch ($msg['content']) {
        case '笑话': {
            $joke = new jokeMaker();
            $response = $joke->getJoke();
            break;
        }
        case 'e':{
            $phoneQuery= new mobilePhoneQuery();
            $response=$phoneQuery->getPrice($msg['content']);
                     $response=$response."\n".'只发个e字，我搜索起来很累的啊喂～～';
            if($response=='以上价格仅供参考，以店面实际报价为准。')$response = '哦，呵呵，你高兴就好了';
            break;
        }
        default: {
            $phoneQuery= new mobilePhoneQuery();
            $response=$phoneQuery->getPrice($msg['content']);
            if($response=='以上报价由慈溪兄弟数码提供，仅供参考，详情请咨询店家')$response = '哦，呵呵，你高兴就好了';
            break;
        }

    }
    $echoStr = $weixin->prepareTextMsg($msg['from'], $msg['me'], $response);
    wxlog($echoStr);
    echo $echoStr;
}
if ($msg['type'] == 'event') {
    if ($msg['EventKey'] == 'abbcdsds') {
//        wxlog('getTheEventKey=' . $msg['EventKey']);
        $joke = new jokeMaker();
        $response = $joke->getJoke();
        $echoStr = $weixin->prepareTextMsg($msg['from'], $msg['me'], $response);
        echo $echoStr;
    }

}
if ($msg['type'] == 'image') {
    $filePath = downloadImgToHost($msg['MediaId']);
    pdoInsert('upload_tbl', array('user_id' => $msg['from'], 'media_id' => $msg['MediaId'], 'file_path' => $filePath));
    $echoStr = $weixin->prepareTextMsg($msg['from'], $msg['me'],  '图片收到了,已放入照片墙');
    echo $echoStr;
}