<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/6/1
 * Time: 14:00
 */
//wxlog('include guessSong ok');

$sourceList = array('null','三只小熊', '最炫民族风', 'take me to your heart', 'see you again', '白月光', '布拉格广场',
    '人生何处不相逢', '匆匆那年', 'high歌', '北国之春', '月半小夜曲', '模特', '平凡之路', '对面的女孩看过来', '夜空中最亮的星', '捉泥鳅');

if ($msg['type'] == 'text') {
    $time = time();
    wxlog('type filt ok');
    if(strlen($msg['content'])>30){
        $sql = 'update guess_tbl set contact="'.$msg['content'].'" where open_id="'.$msg['from'].'"';
        $pdo->exec($sql);
        $response='您发送的信息已作为地址保存，请再次核对地址，姓名，联系电话是否有误，如需修改，重新发送一遍修改过的信息即可';
        $ab=$weixin->prepareTextMsg($msg['from'],$msg['me'],$response);
        echo $ab;
        exit;
    }
    if ($msg['content'] == '排名') {
        $query=pdoQuery('guess_tbl',null,array('weixin_id'=>$weixinId),' order by correct_try desc, update_time asc limit 20');
        $position=1;
        foreach ($query as $row) {
            if($msg['from']==$row['open_id']){
                if($position==1&&$row['correct_try']==16){
                    $response='您在'.date('m.d.H:i:s',$row['update_time']).'时最先猜中所有歌曲获得冠军！恭喜！';
                }elseif($position==1){
                    $response='您现在暂时排在第一位';
                }elseif($row['correct_try']==16){
                    $response='可惜，有人比你抢先猜中了全部歌曲，您现在排在第'.$position.'位';
                }else{
                    $response='您现在排在第'.$position.'位';
                }
                $re=$weixin->prepareTextMsg($msg['from'],$msg['me'],$response);
                echo $re;
                exit;
            }
            $position++;
        }
        $response='sorry,您的成绩未在前20名以内';

        $re=$weixin->prepareTextMsg($msg['from'],$msg['me'],$response);
        echo $re;
        exit;
    }

    $query = pdoQuery('guess_tbl', null, array('open_id' => $msg['from'], 'weixin_id' => $weixinId), ' limit 1');
//    wxlog('query guess_tbl ok');
    if ($row = $query->fetch()) {
//        wxlog('has intent');
        if ($row['total_try'] > 19) {
            wxlog('try max');
            $response = '您已达到最大答题次数，回复“排名”即可查看当前您的成绩排名';
            $res=$weixin->prepareTextMsg($msg['from'],$msg['me'],$response);
            echo $res;
            exit;
        }
        if ($row['correct_try'] == 16) {
            $response = '恭喜您，您猜中了所有歌曲名称，回复“排名”可查看成绩排名';
            $res=$weixin->prepareTextMsg($msg['from'],$msg['me'],$response);
            echo $res;
            exit;
        }
        $songList = json_decode($row['answer'], true);
        reset($songList);
        wxlog('totalTry:'.$row['total_try']);
        if ($key = array_search($msg['content'], $songList)) {
            array_splice($songList, $key, 1);
            $jsonList = json_encode($songList);
            $jsonList = addslashes($jsonList);
            $last=(16 - 1 - $row['correct_try']);
            $sql = 'update guess_tbl set correct_try=correct_try+1,total_try=total_try+1,answer="' . $jsonList . '",update_time=' . $time . ' where open_id="' . $msg['from'] . '" and weixin_id="' . $weixinId . '"';
            $response =($last<1? '全部猜中！回复“排名”即可查看当前排名': '恭喜，猜中一首!!还有' . $last . '首歌') ;
        } else {

            $sql = 'update guess_tbl set total_try=total_try+1,update_time=' . $time . ' where open_id="' . $msg['from'] . '" and weixin_id="' . $weixinId . '"';
            $response =(array_search($msg['content'],$sourceList)==false? '很遗憾，没猜中，还有'.(20-1-$row['total_try']) . '次机会' : '已经猜过了，还有'.(20-1-$row['total_try']) . '次机会');
        }
//        wxlog($sql);
        $pdo->exec($sql);
    }else{
//        wxlog('no intent');
        $songList=$sourceList;
        reset($songList);
        if ($key = array_search($msg['content'], $songList)) {
            wxlog('answer match');
            array_splice($songList, $key, 1);
            $jsonList = json_encode($songList);
            $jsonList = addslashes($jsonList);
            $sql = 'insert guess_tbl set weixin_id="'.$weixinId.'",open_id="'.$msg['from'].'", correct_try=1,total_try=1,answer="' . $jsonList . '",update_time=' . $time;
            $response = '恭喜，猜中一首!!还有' . (16 - 1 - $row['correct_try']) . '首歌';
        } else {
            wxlog($msg['content'].' listcount: '.count($songList));

            foreach ($songList as $i) {
                wxlog('value:'.$i);
            }
            $jsonList = json_encode($songList);
            $jsonList = addslashes($jsonList);
//            wxlog('answer not match');
            $sql = 'insert guess_tbl set weixin_id="'.$weixinId.'",open_id="'.$msg['from'].'", correct_try=0,total_try=1,answer="' . $jsonList . '",update_time=' . $time;
            $response = '很遗憾，没猜中，还有' . (20 - 1 - $row['total_try']) . '次机会';
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
