<?php

/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/4/17
 * Time: 8:38
 */
$mypath = $_SERVER['DOCUMENT_ROOT'] . '/xdsm';
include_once $mypath . '/includes/magicquotes.inc.php';
include_once $mypath . '/includes/db.inc.php';
include_once $mypath . '/includes/xdsmdb.php';
include_once $mypath . '/includes/helpers.inc.php';
include_once $mypath . '/contrller/serveManager.php';
include_once $mypath . '/class/wechat.php';
include_once $mypath . '/class/textHandler.php';
include_once $mypath . '/class/jokeMaker.php';
include_once $mypath . '/class/mobilePhoneQuery.php';


$weObj = new wechat();
//$weObj->valid();
//exit;
$msg = $weObj->receiverFilter();
//$userId = '';

if ($msg['type'] == 'text') {
    switch ($msg['content']) {
        case '笑话': {
            $joke = new jokeMaker();
            $response = $joke->getJoke();
            break;
        }

        default: {
            $phoneQuery= new mobilePhoneQuery();
            $response=$phoneQuery->getPrice($msg['content']);
            if($response=='以上价格仅供参考，以店面实际报价为准。')$response = '兄弟数码客服，为您服务';
            break;
        }

    }
    $echoStr = $weObj->prepareMsg($msg['from'], $msg['me'], 'text', $response);
    echo $echoStr;
}
if ($msg['type'] == 'event') {
    if ($msg['EventKey'] == 'abbcdsds') {
        $joke = new jokeMaker();
        $response = $joke->getJoke();
        $echoStr = $weObj->prepareMsg($msg['from'], $msg['me'], 'text', $response);
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

        $echoStr = $weObj->prepareMsg($msg['from'], $msg['me'], 'text', $content);
        echo $echoStr;
    }

}
if ($msg['type'] == 'image') {
    $filePath = downloadImgToHost($msg['MediaId']);
    pdoInsert('upload_tbl', array('user_id' => $msg['from'], 'media_id' => $msg['MediaId'], 'file_path' => $filePath));
    $echoStr = $weObj->prepareMsg($msg['from'], $msg['me'], 'text', '图片收到了');
    echo $echoStr;
}
if(!isset($userId)){
    $userId=getUnionId($msg['from'])['nickname'];
}
wxlog('receive Content: ' . $msg['content'].'  from: '.$userId);