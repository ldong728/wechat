<?php
$mypath = $_SERVER['DOCUMENT_ROOT'] . '/wechat';
include_once $mypath . '/includes/magicquotes.inc.php';
include_once $mypath . '/includes/db.inc.php';
include_once $mypath . '/includes/helpers.inc.php';
session_start();
//date_default_timezone_set('Asia/Shanghai');
if(isset($_SESSION['login'])&&$_SESSION['login']){
    $query = pdoQuery('user_tbl',array('token'),array('weixin_id'=>$_SESSION['weixinId']),' limit 1');
    $row=$query->fetch();
    $token=$row['token'];
    printView('/admin/view/index.html.php','首页');
}else{
    header('location: ../login/index.php');

}


