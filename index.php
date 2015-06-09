<?php
$mypath = $_SERVER['DOCUMENT_ROOT'] . '/wechat';
include_once $mypath . '/includes/magicquotes.inc.php';
include_once $mypath . '/includes/db.inc.php';
include_once $mypath . '/includes/helpers.inc.php';
include_once $mypath. '/class/wechat.php';
if(isset($_GET['id'])){
    wxlog('fetch,to:'.$_GET['id']);
    $weixinId=$_GET['id'];
    $query=pdoQuery('duty_tbl',array('duty'),array('weixin_id'=>$weixinId),' limit 1');
    $data=$query->fetch();
        $decodeData=json_decode($data['duty'],true);
        $weixin=new wechat($weixinId);
        $weixin->valid();
        $msg=$weixin->receiverFilter();
        foreach ($decodeData['dutyContent'] as $row) {
//            wxlog('include:'.$mypath.'/'.$row);
            include_once $mypath.'/'.$row;
        }
    echo '';
    eixt;
}
header('location: admin/index.php');
?>