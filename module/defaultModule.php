<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/5/25
 * Time: 13:29
 */
include_once $mypath . '/class/interfaceHandler.php';

wxlog('include default ok');
$query = pdoQuery('default_reply_tbl', null, array('weixin_id' => $weixinId), ' order by key_word desc limit 10');
//$queryArray=$query->fetchAll();
foreach ($query as $row) {
    if (preg_match('/' . $row['key_word'] . '/', $msg['Content'])) {
        switch ($row['reply_type']) {
            case 'text': {
                $weixin->replyMsg(array('Content'=>$row['content'],'MsgType'=>'text',));
//                $response=$weixin->prepareTextMsg($msg['from'],$msg['me'],$row['content']);
//                echo $response;
                exit;
                break;
            }
            case 'news': {
                            $response=$weixin->prepareNewsMsg($msg['from'],$msg['me'],$row['content']);
                             echo $response;
                             exit;
                             break;
            }
        }
    }
}


//$response = $weixin->prepareTextMsg($msg['from'], $msg['me'], );
$response = '';
echo $response;
exit;
