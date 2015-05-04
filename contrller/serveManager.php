<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/4/21
 * Time: 14:15
 */
$mypath = $_SERVER['DOCUMENT_ROOT'] . '/wechat';
include_once $mypath . '/class/interfaceHandler.php';

$mInterface = new interfaceHandler();


function deleteButton()
{
    $data = $GLOBALS['mInterface']->sendGet('https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=' . $GLOBALS['mInterface']->currentToken);
    echo $data;
    echo 'delete ok';
    wxlog('delete all button');
}

function createButton()
{
    $button1 = array('type' => 'click', 'name' => '讲个笑话', 'key' => 'abbcdsds');
    $serchSubButton = array('type' => 'view', 'name' => 'js页面测试', 'url' => 'http://115.29.202.69/wechat/js');
    $videoSubButton = array('type' => 'view', 'name' => '网页测试', 'url' => 'http://115.29.202.69/wechat');
    $praiseSubButton = array('type' => 'click', 'name' => '会员卡页面测试', 'key' => 'cards');
    $button2 = array('name' => '链接跳转', 'sub_button' => array($serchSubButton, $videoSubButton, $praiseSubButton));
    $button3 = array('type' => 'view', 'name' => '照片墙', 'url' => 'http://115.29.202.69/wechat/gallery');
    $mainButton = array('button' => array($button1, $button2, $button3));
    $jsondata = json_encode($mainButton, JSON_UNESCAPED_UNICODE);
    echo $jsondata;
    $response = $GLOBALS['mInterface']->postJsonByCurl('https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $GLOBALS['mInterface']->currentToken, $jsondata);
    echo $response;

}


function createNewKF($account_name, $name, $psw)
{
    $password = md5($psw);
    $createInf = array('kf_account' => $account_name . '@' . wexinId, 'nickname' => $name, 'password' => $password);
    $json = json_encode($createInf, JSON_UNESCAPED_UNICODE);
    echo $json . "\n";
    $data = $GLOBALS['mInterface']->postJsonByCurl('https://api.weixin.qq.com/customservice/kfaccount/add?access_token=ACCESS_TOKEN', $json);
    return $data;

}

function uploadTempMedia($file, $type)
{
    $localSavePath = $GLOBALS['mypath'] . '/tmpmedia/' . $file['name'];
    move_uploaded_file($file['tmp_name'], $localSavePath);
    $back = $GLOBALS['mInterface']->uploadFileByCurl('https://api.weixin.qq.com/cgi-bin/media/upload?access_token=ACCESS_TOKEN&type=' . $type, $localSavePath);
    $upInf = json_decode($back, true);
    if (isset($upInf['media_id'])) {
        pdoInsert('up_temp_tbl', array('local_name' => $localSavePath, 'media_id' => $upInf['media_id'], 'expires_time' => $upInf['created_at'] + 259200, 'media_type' => $type));
        return '上传成功';
    } else {
        output('上传错误，错误代码：' . $upInf['errcode']);
    }
}

function downloadImgToHost($media_id)
{
    $url='https://api.weixin.qq.com/cgi-bin/media/get?access_token=ACCESS_TOKEN&media_id=';
    $imgData = $GLOBALS['mInterface']->getByCurl($url.$media_id);
    $filePath='tmpmedia/'.$media_id.'.jpg';
    file_put_contents($filePath,$imgData);
    return $filePath;
}
function getUnionId($openId){
    $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=ACCESS_TOKEN&openid='.$openId.'&lang=zh_CN';
    $jsonData=$GLOBALS['mInterface']->getByCurl($url);
    return json_decode($jsonData,true);;
}
