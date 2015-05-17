<?php
$mypath = $_SERVER['DOCUMENT_ROOT'] . '/wechat';
include_once $mypath . '/includes/magicquotes.inc.php';
include_once $mypath . '/includes/db.inc.php';
include_once $mypath . '/includes/helpers.inc.php';
wxlog('fetch');
//include $mypath.'/contrller/msgManager.php';
include_once $mypath.'/contrller/wechatWall.php';


?>