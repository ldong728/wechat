<?php
$mypath = $_SERVER['DOCUMENT_ROOT'] . '/wechat';
include_once $mypath . '/includes/magicquotes.inc.php';
include_once $mypath . '/includes/db.inc.php';
include_once $mypath . '/includes/helpers.inc.php';
include_once $mypath. '/class/wechat.php';


//include_once $mypath.'/contrller/wechatWall.php';
if(isset($_GET['id'])){
    wxlog('fetch,to:'.$_GET['id']);
    $weixinId=$_GET['id'];
    $query=pdoQuery('duty_tbl',array('duty'),array('weixin_id'=>$weixinId),' limit 1');
    $data=$query->fetch();
    wxlog('queryOK');
//    if($data!=false){
        wxlog($data['duty']);
        $decodeData=json_decode($data['duty'],true);
        wxlog($decodeData['dutyContent'][0]);
        $weixin=new wechat($weixinId);
        wxlog('have new weixin');
        $weixin->valid();
        $msg=$weixin->receiverFilter();
        wxlog('filter ok');
        foreach ($decodeData['dutyContent'] as $row) {
            wxlog('problem');
            include_once $mypath.'/'.$row;
            wxlog($mypath.'/'.$row);
        }


//    }

}


//    include $mypath.'/contrller/wechatWall.php';
//}


?>