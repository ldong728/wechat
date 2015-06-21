<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/6/1
 * Time: 14:00
 */

// 配置信息json：    {"config":{"startTime":"0","stopTime":"0","key_word":"测试","preTimeResponse":"","timeupResponse":"","totalTry":"10","rank":"5"},
//                  "inputType":["datetime-local","datetime-local","text","text","text","text","text"],
//                  "configInf":["开始时间","结束时间","关键词（用逗号分隔）","游戏开始前的回复","游戏结束后的回复","总尝试次数","排行榜总人数"]}

$startTime=strtotime($config['startTime']);
$stopTime=strtotime($config['stopTime']);
$totalTry=(int)$config['totalTry'];
$rank=(int)$config['rank'];

$stopTime=$stopTime<$startTime? $startTime:$stopTime;
if(time()<$startTime){

$weixin->replytext($config['preTimeResponse']);
    exit;

}
if(time()>$stopTime){
$weixin->replytext($config['timeupResponse']);
exit;

}
if ($msg['type'] == 'text') {
    $time = time();
    wxlog('type filt ok');
    if(strlen($msg['content'])>30){
        $sql = 'update guess_tbl set contact="'.$msg['Content'].'" where open_id="'.$msg['from'].'"';
        $pdo->exec($sql);
        $response='您发送的信息已作为地址保存，请再次核对地址，姓名，联系电话是否有误，如需修改，重新发送一遍修改过的信息即可';
        $ab=$weixin->prepareTextMsg($msg['from'],$msg['me'],$response);
        echo $ab;
        exit;
    }
    if ($msg['Content'] == '排名') {
        $query=pdoQuery('guess_tbl',null,array('weixin_id'=>$weixinId),' order by correct_try desc, update_time asc limit '.$rank);
        $position=1;
        foreach ($query as $row) {
            if($msg['from']==$row['open_id']){
                if($position==1&&$row['answer']=='["null"]'){
                    $response='您在'.date('m.d.H:i:s',$row['update_time']).'时最先完成竞猜获得冠军！恭喜！';
                }elseif($position==1){
                    $response='您现在暂时排在第一位';
                }elseif($row['answer']=='null'){
                    $response='可惜，有人比你抢先猜中了全部，您现在排在第'.$position.'位';
                }else{
                    $response='您现在排在第'.$position.'位';
                }
                $re=$weixin->prepareTextMsg($msg['from'],$msg['me'],$response);
                echo $re;
                exit;
            }
            $position++;
        }
        $response='sorry,您的成绩未在前'.$rank.'名以内';

        $re=$weixin->prepareTextMsg($msg['from'],$msg['me'],$response);
        echo $re;
        exit;
    }

    $query = pdoQuery('guess_tbl', null, array('open_id' => $msg['from'], 'weixin_id' => $weixinId), ' limit 1');
//    wxlog('query guess_tbl ok');
    if ($row = $query->fetch()) {
        $songList = json_decode($row['answer'], true);
        $correctLast=count($songList);
        reset($songList);
//        wxlog('has intent');
        if ($row['total_try'] > $totalTry-1) {
            wxlog('try max');
            $response = '您已达到最大答题次数，回复“排名”即可查看当前您的成绩排名';
            $res=$weixin->prepareTextMsg($msg['from'],$msg['me'],$response);
            echo $res;
            exit;
        }
        if ($correctLast<2) {
            $response = '恭喜您，您全部猜对了，回复“排名”可查看成绩排名';
            $res=$weixin->prepareTextMsg($msg['from'],$msg['me'],$response);
            echo $res;
            exit;
        }



//        wxlog('totalTry:'.$row['total_try']);
        if ($key = array_search($msg['Content'], $songList)) {
            array_splice($songList, $key, 1);
            $jsonList = json_encode($songList);
            $jsonList = addslashes($jsonList);
//            $last=($correctTry - 1 - $row['correct_try']);
            $sql = 'update guess_tbl set correct_try=correct_try+1,total_try=total_try+1,answer="' . $jsonList . '",update_time=' . $time . ' where open_id="' . $msg['from'] . '" and weixin_id="' . $weixinId . '"';
            $response =($correctLast<3? '全部猜中！回复“排名”即可查看当前排名': '恭喜，猜中一题!!还有' . ($correctLast-2) . '题') ;
        } else {
            $sourceList=formatSourceFromConfig($config['key_word']);
            $sql = 'update guess_tbl set total_try=total_try+1,update_time=' . $time . ' where open_id="' . $msg['from'] . '" and weixin_id="' . $weixinId . '"';
            $response =(array_search($msg['Content'],$sourceList)==false? '很遗憾，没猜中，还有'.($totalTry-1-$row['total_try']) . '次机会' : '已经猜过了，还有'.($totalTry-1-$row['total_try']) . '次机会');
        }
//        wxlog($sql);
        $pdo->exec($sql);
    }else{
//        wxlog('no intent');
        $songList=formatSourceFromConfig($config['key_word']);;
        $correctLast=count($songList);
        reset($songList);
        if ($key = array_search($msg['Content'], $songList)) {
//            wxlog('answer match');
            array_splice($songList, $key, 1);
            $jsonList = json_encode($songList);
            $jsonList = addslashes($jsonList);
            $sql = 'insert guess_tbl set weixin_id="'.$weixinId.'",open_id="'.$msg['from'].'", correct_try=1,total_try=1,answer="' . $jsonList . '",update_time=' . $time;
            $response = '恭喜，猜中一题!!还有' . ($correctLast-2) . '题';
        } else {
//            wxlog($msg['Content'].' listcount: '.count($songList));
//
//            foreach ($songList as $i) {
//                wxlog('value:'.$i);
//            }
            $jsonList = json_encode($songList);
            $jsonList = addslashes($jsonList);
//            wxlog('answer not match');
            $sql = 'insert guess_tbl set weixin_id="'.$weixinId.'",open_id="'.$msg['from'].'", correct_try=0,total_try=1,answer="' . $jsonList . '",update_time=' . $time;
            $response = '很遗憾，没猜中，还有' . ($totalTry - 1 - $row['total_try']) . '次机会';
        }

        $pdo->exec($sql);
//        wxlog('exec OK');


    }
//    wxlog('logic ovew');
    $res=$weixin->prepareTextMsg($msg['from'],$msg['me'],$response);
    echo $res;
    exit;




} else {
    $res=$weixin->prepareTextMsg($msg['from'],$msg['me'],'请回复一首歌名');
    echo $res;
    exit;
}
function formatSourceFromConfig($source){
    $formatSource=trim($source);
    $formatSource=preg_replace('/^/','["null,',$formatSource);
    $formatSource=preg_replace('/$/','"]',$formatSource);
    $formatSource=preg_replace('/,|，/','","',$formatSource);
    $value=json_decode($formatSource,true);
    return $value;
}
//function replytext($resp){
//    $ab = $GLOBALS['weixin']->prepareTextMsg($GLOBALS['msg']['from'],$GLOBALS['msg']['me'],$resp);
//    echo $ab;
//    exit;
//}
