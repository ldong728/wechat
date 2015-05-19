<?php

$mypath = $_SERVER['DOCUMENT_ROOT'] . '/wechat';
//include_once $mypath . '/includes/magicquotes.inc.php';
//include_once $mypath . '/includes/db.inc.php';
//include_once $mypath . '/includes/helpers.inc.php';
include_once $mypath . '/class/wechat.php';
include_once $mypath . '/contrller/serveManager.php';


wxlog('include ok');
$myWechat = new wechat();
$msg=$myWechat->receiverFilter();
$userInf=getUnionId($msg['from']);
$userName=$userInf['nickname'];
$userIcon = $userInf['headimgurl'];
$sex=$userInf['sex'];
$url='insert into wechat_wall_tbl set user_name="'.$userName.'",sex="'.$sex.'",user_icon="'.$userIcon.'",content=:content,img_url=:imgUrl,upload_time=:uploadTime';



if($msg['type']=='text'){
//    $exe=$pdo->prepare($url);
//    $exe->bindValue(':content',$msg['content']);
//    $exe->bindValue(':imgurl','0');
//    $exe->bindValue('uploadTime',time());
//    $exe->execute();
//    wxlog(microtime(true));
//    $insertTime = (int)(microtime(true)*1000);
//    wxlog($insertTime);
    pdoInsert('wechat_wall_tbl',array('user_name'=>$userName,'sex'=>$sex,'user_icon'=>$userIcon,'content'=>$msg['content'],'upload_time'=>time()));

}
if($msg['type']=='image'){
    pdoInsert('wechat_wall_tbl',array('user_name'=>$userName,'sex'=>$sex,'user_icon'=>$userIcon,'img_url'=>$msg['PicUrl'],'upload_time'=>time()));
}

$echoMsg=$myWechat->prepareTextMsg($msg['from'],$msg['me'],'收到了');
echo $echoMsg;


exit;