<?php
$mypath = $_SERVER['DOCUMENT_ROOT'] . '/xdsm';
include_once $mypath . '/includes/magicquotes.inc.php';
include_once $mypath . '/includes/db.inc.php';
include_once $mypath . '/includes/helpers.inc.php';
require_once $mypath.'/class/jssdk.php';


if(isset($_GET['location'])){
    wxlog('get location');
    $jssdk = new JSSDK();
    $signPackage = $jssdk->GetSignPackage();
    include 'location.html.php';
    wxlog('location done');
    exit;
}



if(isset($_GET['homepage'])){
    header('location: http://www.xdsm.net');
    exit;
}

output('网站正在建设中，请稍候');

/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/5/5
 * Time: 11:06
 */ 