<?php
$mypath = $_SERVER['DOCUMENT_ROOT'] . '/wechat';
include_once $mypath . '/includes/magicquotes.inc.php';
include_once $mypath . '/includes/db.inc.php';
include_once $mypath . '/includes/helpers.inc.php';

//include_once $mypath.'/contrller/wechatWall.php';
if(isset($_GET['id'])){
    wxlog($_GET['id']);
    $weixinId=$_GET['id'];
    include $mypath.'/contrller/wechatWall.php';
}


?>