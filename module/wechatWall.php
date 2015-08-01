<?php

//$mypath = $_SERVER['DOCUMENT_ROOT'] . '/wechat';
//include_once $mypath . '/includes/magicquotes.inc.php';
//include_once $mypath . '/includes/db.inc.php';
//include_once $mypath . '/includes/helpers.inc.php';
//include_once $mypath . '/class/wechat.php';
include_once $mypath . '/contrller/serveManager.php';


wxlog('include wechatWall ok');
$userInf=getUnionId($msg['from'],$msg['me']);
$userName=$userInf['nickname'];
$userIcon = $userInf['headimgurl'];
$sex=$userInf['sex'];
$url='insert into wechat_wall_tbl set user_name="'.$userName.'",sex="'.$sex.'",user_icon="'.$userIcon.'",content=:content,img_url=:imgUrl,upload_time=:uploadTime';


wxlog('msgType:'.$$msg['type']);
if($msg['MsgType']=='text'){

    pdoInsert('wechat_wall_tbl',array('owner'=>$msg['me'],'user_name'=>$userName,'sex'=>$sex,'user_icon'=>$userIcon,'content'=>$msg['content'],'upload_time'=>time()));
//    wxlog('insertOk');
}
if($msg['MsgType']=='image'){
    pdoInsert('wechat_wall_tbl',array('owner'=>$msg['me'],'user_name'=>$userName,'sex'=>$sex,'user_icon'=>$userIcon,'img_url'=>$msg['PicUrl'],'upload_time'=>time()));
}

//$echoMsg=$weixin->prepareTextMsg($msg['from'],$msg['me'],'收到了');
//echo $echoMsg;
$weixin->replytext("收到了！");
exit;