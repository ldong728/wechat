<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/5/28
 * Time: 13:55
 */

// 配置信息json：    {"config":{"kfKeyWord":"客服","evaluate":"1"},"inputType":["text","checkbox"],"configInf":["开启客服模式的关键词","开启客服评分模式"]}
include_once $mypath."/class/kf.php";
$myKf=new kf($weixinId);
if(isset($msg['Event'])){
    wxlog($msg['Event']);
    if($msg['Event']=='kf_close_session'){
        if($config['evaluate']==0){
            pdoUpdate('kf_tbl', array('kf_on' => 0), array('open_id' => $msg['FromUserName']));
            $myKf->sendKfMsg('客服会话已关闭，回复“'.$config['kfKeyWord'].'”重新连接客服',$msg['FromUserName']);
        }else{
            pdoUpdate('kf_tbl', array('kf_on' => 3), array('open_id' => $msg['FromUserName']));
            $myKf->sendKfMsg('客服会话已关闭，回复数字1-10即可对客服评分，回复“'.$config['kfKeyWord'].'”重新连接客服',$msg['FromUserName']);
        }
    }
}


if(isset($msg['Content'])) {
    $query = pdoQuery('kf_tbl', array('kf_on'), array('open_id' => $msg['FromUserName']), 'limit 1');
    $row = $query->fetch();
//    wxlog($row['kf_on']);
    if ($row && $row['kf_on'] == 1) {
//        wxlog('select kf');
        $kfList = $myKf->getOnlineKf();
        $replyed = false;
        foreach ($kfList as $i) {
            if ($msg['Content'] == $i['kf_id'] || $msg['Content'] == $i['kf_nick']) {
//                wxlog('kf_match:' . $i['kf_account']);
                $response = $weixin->prepareToKFMsg($i['kf_account']);
//                wxlog($response);
                echo $response;
                $myKf->sendKfMsg('已为您转接至客服：'.$i['kf_nick'].'， 请稍候...',$msg['FromUserName']);
                $replyed = true;
                pdoUpdate('kf_tbl', array('kf_on' => 2), array('open_id' => $msg['FromUserName']));
                break;
            }
        }
        if (!$replyed) {
//            wxlog('not select');
            $response = $weixin->prepareToKFMsg();
            echo $response;
            $myKf->sendKfMsg('已为您转接至客服， 请稍候...',$msg['FromUserName']);
            pdoUpdate('kf_tbl', array('kf_on' => 2), array('open_id' => $msg['FromUserName']));

        }

    }
    if ($row && $row['kf_on'] == 3) {
        if($msg['Content']>0&&$msg['Content']<11){

            $myKf->sendKfMsg('评分已录入，您的评分将作为客服的考评依据',$msg['FromUserName']);
        }
        pdoUpdate('kf_tbl', array('kf_on' => 0), array('open_id' => $msg['FromUserName']));

    }
    if ($msg['Content'] == $config['kfKeyWord']) {

        pdoInsert('kf_tbl', array('weixin_id' => $weixinId, 'open_id' => $msg['FromUserName'], 'kf_on' => 1,),
            ' ON DUPLICATE KEY update kf_on=1');
//        wxlog('insertOK');
        $kfList = $myKf->getOnlineKf();
//        wxlog('getkflist ok');
        if ($kfList) {
//            wxlog('kflist exist');
            $responseStr = "当前在线客服列表：" . "\n";
            foreach ($kfList as $row) {
//                wxlog('foreach');
                $responseStr = $responseStr . '工号:' . $row['kf_id'] . ' 昵称：' . $row['kf_nick'] . "\n\n";
            }
            $responseStr = $responseStr . '回复工号或昵称指定客服，或直接回复内容自动接入当前空闲客服';
        } else {
            $responseStr = '当前无客服在线，您可以直接留言，客服会在上线后第一时间联系您';
        }
//        wxlog($responseStr);
        $weixin->replytext($responseStr);

    }
}

