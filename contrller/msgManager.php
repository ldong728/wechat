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

wxlog('get msg');
$weObj = new wechat($weixinId);
$weObj->valid();
$msg = $weObj->receiverFilter();
wxlog('filter return ok content:'. $msg['content']);
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
            if($response=='以上价格仅供参考，以店面实际报价为准。')$response = '哦，呵呵，你高兴就好了';
            break;
        }

    }
    $echoStr = $weObj->prepareTextMsg($msg['from'], $msg['me'], $response);
    wxlog($echoStr);
    echo $echoStr;
}
if ($msg['type'] == 'event') {
    if ($msg['EventKey'] == 'abbcdsds') {
//        wxlog('getTheEventKey=' . $msg['EventKey']);
        $joke = new jokeMaker();
        $response = $joke->getJoke();
        $echoStr = $weObj->prepareTextMsg($msg['from'], $msg['me'], $response);
        echo $echoStr;
    }
    if($msg['EventKey']=='cards'){
        $content = 'http://m.1ka1.cn/RecruitMember.aspx?SID=AQUAAAAAAAUVAAAAFpFJzybbPjb4RuuSI2wCAA%3d%3d&WeiXinId=';
        $content=$content.$msg['from'];
//        $temp = getUnionId($msg['from']);
//        $userId=$temp['nickname'];
//        $content="用户信息：\n";
//        foreach ( $temp as $k=>$v) {
//            $content=$content.$k.':  '.$v."\n";
//        }

        $echoStr = $weObj->prepareTextMsg($msg['from'], $msg['me'], $content);
        echo $echoStr;
    }

}
if ($msg['type'] == 'image') {
    $filePath = downloadImgToHost($msg['MediaId']);
    pdoInsert('upload_tbl', array('user_id' => $msg['from'], 'media_id' => $msg['MediaId'], 'file_path' => $filePath));
    $echoStr = $weObj->prepareTextMsg($msg['from'], $msg['me'],  '图片收到了,已放入照片墙');
    echo $echoStr;
}
if(!isset($userId)){
    $userId=getUnionId($msg['from'],$weixinId)['nickname'];
}
wxlog('receive Content: ' . $msg['content'].'  from: '.$userId);