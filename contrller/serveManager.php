<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/4/21
 * Time: 14:15
 */
session_start();
$mypath = $_SERVER['DOCUMENT_ROOT'] . '/wechat';
include_once $mypath . '/class/interfaceHandler.php';

$ready = false;

if (isset($_SESSION['weixinId'])) {
    $mInterface = new interfaceHandler($_SESSION['weixinId']);
    $ready = true;
}


function deleteButton($weixinId = 0)
{
    $itfc = ($GLOBALS['ready'] ? $GLOBALS['mInterface'] : new interfaceHandler($weixinId));
    $data = $itfc->sendGet('https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=ACCESS_TOKEN');
    echo $data;
    echo 'delete ok';
    wxlog('delete all button');
}

function createButtonTemp($weixinId = 0)
{
    $itfc = ($GLOBALS['ready'] ? $GLOBALS['mInterface'] : new interfaceHandler($weixinId));
    $button1 = array('type' => 'click', 'name' => '讲个笑话', 'key' => 'abbcdsds');
    $serchSubButton = array('type' => 'view', 'name' => 'js接口测试', 'url' => 'http://115.29.202.69/wechat/js?weixin_id=' . $itfc->weixinId);
    $videoSubButton = array('type' => 'view', 'name' => '网页测试', 'url' => 'http://115.29.202.69/wechat');
    $praiseSubButton = array('type' => 'click', 'name' => '会员卡页面测试', 'key' => 'cards');
    $button2 = array('name' => '链接跳转', 'sub_button' => array($serchSubButton, $videoSubButton, $praiseSubButton));
    $button3 = array('type' => 'view', 'name' => '照片墙', 'url' => 'http://115.29.202.69/wechat/gallery');
    $mainButton = array('button' => array($button1, $button2, $button3));
    $jsondata = json_encode($mainButton, JSON_UNESCAPED_UNICODE);
    echo $jsondata;
    $response = $itfc->postJsonByCurl('https://api.weixin.qq.com/cgi-bin/menu/create?access_token=ACCESS_TOKEN', $jsondata);
    echo $response;

}
//function getButtonInfo(){
//    $hander=new interfaceHandler($_SESSION['weixinId']);
//    $response = $hander->getByCurl('https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token=ACCESS_TOKEN');
//    return $response;
//}

function getMenuInf()
{
    $json = $GLOBALS['mInterface']->getByCurl('https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token=ACCESS_TOKEN');
    return $json;
}

function creatButton($json)
{

}


function createNewKF($account_name, $name, $psw)
{
//    $itfc = ($GLOBALS['ready'] ? $GLOBALS['mInterface'] : new interfaceHandler($weixinId));
    $password = md5($psw);
    $createInf = array('kf_account' => $account_name . '@' . wexinId, 'nickname' => $name, 'password' => $password);
    $json = json_encode($createInf, JSON_UNESCAPED_UNICODE);
    echo $json . "\n";
    $data = $GLOBALS['mInterface']->postJsonByCurl('https://api.weixin.qq.com/customservice/kfaccount/add?access_token=ACCESS_TOKEN', $json);
    return $data;

}
function getKFinf(){
    $data=$GLOBALS['mInterface']->getByCurl('https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token=ACCESS_TOKEN');
    return $data;
}

function uploadTempMedia($file, $type, $weixinId = 0)
{
    $itfc = ($GLOBALS['ready'] ? $GLOBALS['mInterface'] : new interfaceHandler($weixinId));
    $localSavePath = $GLOBALS['mypath'] . '/tmpmedia/' . $file['name'];
    move_uploaded_file($file['tmp_name'], $localSavePath);
    $back = $itfc->uploadFileByCurl('https://api.weixin.qq.com/cgi-bin/media/upload?access_token=ACCESS_TOKEN&type=' . $type, $localSavePath);
    $upInf = json_decode($back, true);
    if (isset($upInf['media_id'])) {
        pdoInsert('up_temp_tbl', array('local_name' => $localSavePath, 'media_id' => $upInf['media_id'], 'expires_time' => $upInf['created_at'] + 259200, 'media_type' => $type));
        return '上传成功';
    } else {
        output('上传错误，错误代码：' . $upInf['errcode']);
    }
}

function downloadImgToHost($media_id, $weixinId = 0)
{
    $itfc = ($GLOBALS['ready'] ? $GLOBALS['mInterface'] : new interfaceHandler($weixinId));
    $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token=ACCESS_TOKEN&media_id=';
    $imgData = $itfc->getByCurl($url . $media_id);
    $filePath = 'tmpmedia/' . $media_id . '.jpg';
    file_put_contents($filePath, $imgData);
    return $filePath;
}

function getUnionId($openId, $weixinId = 0)
{
    if ($GLOBALS['ready']) {
        $itfc = $GLOBALS['mInterface'];
    } else {
        $itfc = new interfaceHandler($weixinId);
    }
    $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=ACCESS_TOKEN&openid=' . $openId . '&lang=zh_CN';
    $jsonData = $itfc->getByCurl($url);
    return json_decode($jsonData, true);
}



function getMediaList($type, $offset)
{
    $request = array('type' => $type, 'offset' => $offset, 'count' => 20);
    $json = $GLOBALS['mInterface']->postArrayAsJson('https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=ACCESS_TOKEN', $request);
    return json_decode($json, true);
}

function getMedia($jsonMediaId)
{
//    $itfc=($GLOBALS['ready']?$GLOBALS['mInterface']:new interfaceHandler($weixinId) );
    $json = $GLOBALS['mInterface']->postJsonByCurl('https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=ACCESS_TOKEN', $jsonMediaId);
    return $json;
}

function reflashAutoReply()
{
    $replyinf = $GLOBALS['mInterface']->getByCurl('https://api.weixin.qq.com/cgi-bin/get_current_autoreply_info?access_token=ACCESS_TOKEN');
//    output(addslashes($replyinf));
//    exit;
    $replyRule = json_decode($replyinf, true);
    if ($replyRule['is_autoreply_open'] == 1) {
        if (isset($replyRule['add_friend_autoreply_info'])) {
            $readyContent=formatContent($replyRule['add_friend_autoreply_info']['type'],$replyRule['add_friend_autoreply_info']['content']);
            $readyContent['request_type']='event';
            $readyContent['key_word']='add_friend_autoreply_info';
            $readyContent['update_time']=time();
            pdoInsert('default_reply_tbl', $readyContent, ' ON DUPLICATE KEY UPDATE content="' .$readyContent['content']. '",update_time='.time());
        }

        foreach ($replyRule['keyword_autoreply_info']['list'] as $row) {
            $readyContent=formatContent( $row['reply_list_info'][0]['type'],$row['reply_list_info'][0]['news_info']['list']);
            $readyContent['key_word'] = $row['keyword_list_info'][0]['content'];
            pdoInsert('default_reply_tbl', $readyContent, ' ON DUPLICATE KEY UPDATE content="' .$readyContent['content']. '",update_time='.time());
//            $reContent = json_encode(array('news_item' => $content));

        }
    }


}

function formatContent($type, $content)
{
    $insertArray['reply_type']=$type;
    $insertArray['weixin_id']=$_SESSION['weixinId'];
    $insertArray['source']=1;
    switch ($type) {
        case 'text': {
            $insertArray['content']=$content;
                         break;
        }
        case 'news':{
            $data=formatNewsContent($content);
            $insertArray['content']=$data;
                break;
        }
            default:{

                break;
            }
    }
    return $insertArray;

}

function formatNewsContent(array $contentArray)
{
    $content = json_encode(array('news_item' => $contentArray),JSON_UNESCAPED_UNICODE);
    $content = addslashes($content);
    return $content;
}
