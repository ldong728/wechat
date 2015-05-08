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
$msg = $weObj->receiverFilter();

if ($msg['type'] == 'text') {
    switch ($msg['content']) {
        case '笑话': {
            $joke = new jokeMaker();
            $response = $joke->getJoke();
            $echoStr = $weObj->prepareTextMsg($msg['from'], $msg['me'], $response);
            echo $echoStr;
            break;
        }
//            case'手机'

        default: {
        if (preg_match('/手机/', $msg['content'])) {
            $response = '如要查询手机价格，请直接回复手机型号关键字，如“plus”、“5s”、“air”等';
            break;
        }
        $phoneQuery = new mobilePhoneQuery();
        $response = $phoneQuery->getPrice($msg['content']);
        if ($response == '以上价格仅供参考，以店面实际报价为准。') {
            $response = '您好，您输入的内容系统无法识别，已转接至人工客服，请稍候';
            $echoStr = $weObj->prepareToKFMsg($msg['from'], $msg['me']);
            echo $echoStr;
            $temp = (string)$msg['from'];
            $respnseArray = array('touser' => $temp, 'msgtype' => 'text', 'text' => array('content' => $response));
            sendMsg(json_encode($respnseArray, JSON_UNESCAPED_UNICODE));
            break;

        } else {
            $echoStr = $weObj->prepareTextMsg($msg['from'], $msg['me'], $response);
            echo $echoStr;

        }


        }

    }

}
if ($msg['type'] == 'event') {
    if ($msg['EventKey'] == 'abbcdsds') {
        $joke = new jokeMaker();
        $response = $joke->getJoke();
        $echoStr = $weObj->prepareTextMsg($msg['from'], $msg['me'], $response);
        echo $echoStr;
    }
    if ($msg['EventKey'] == 'cards') {

        $content = 'http://m.1ka1.cn/RecruitMember.aspx?SID=AQUAAAAAAAUVAAAAFpFJzybbPjb4RuuSI2wCAA%3d%3d&WeiXinId=';
        $content = $content . $msg['from'];
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
    $echoStr = $weObj->prepareTextMsg($msg['from'], $msg['me'], '图片收到了');
    echo $echoStr;
}
if (!isset($userId)) {
    $userId = getUnionId($msg['from'])['nickname'];
}
wxlog('receive Content: ' . $msg['content'] . '  from: ' . $userId);