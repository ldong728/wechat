<?php
$mypath=$_SERVER['DOCUMENT_ROOT'] . '/wechat';
include_once $mypath . '/includes/magicquotes.inc.php';
include_once $mypath . '/includes/db.inc.php';
include_once $mypath . '/includes/helpers.inc.php';
require_once $mypath.'/class/jssdk.php';


$jssdk = new JSSDK();
$signPackage = $jssdk->GetSignPackage();

include 'view.html.php'
//include 'draw.html';
//header('location: draw.html');

?>




