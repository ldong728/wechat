<?php
$mypath = $_SERVER['DOCUMENT_ROOT'] . '/wechat';
include_once $mypath . '/includes/magicquotes.inc.php';
include_once $mypath . '/includes/db.inc.php';
include_once $mypath . '/includes/helpers.inc.php';
require_once $mypath.'/class/jssdk.php';
include_once $mypath . '/contrller/serveManager.php';


if(isset($_GET['location'])){
    $jssdk = new JSSDK();
    $signPackage = $jssdk->GetSignPackage();
    include 'location.html.php';
    exit;
}



if(isset($_GET['homepage'])){
    header('location: http://www.xdsm.net');
    exit;
}

if(isset($_GET['state'])){
    if(isset($_GET['code'])){
//        wxlog('code:'.$_GET['code']);
        $code=$_GET['code'];
        $jsonData=authorize($code);
        echo $jsonData;
        $data=json_decode($jsonData,true);
        $userInf=getUserInfFromAuthorze($data['access_token'],$data['openid']);
        echo $userInf['nickname'];
        header('location:http://m.1ka1.cn/RecruitMember.aspx?SID=AQUAAAAAAAUVAAAAFpFJzybbPjb4RuuSI2wCAA%3d%3d&WeiXinId='.$userInf['nickname']);
        exit;
    }
    output('code geted');
    exit;
}

output('网站正在建设中，请稍候');

/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/5/5
 * Time: 11:06
 */ 