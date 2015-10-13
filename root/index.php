<?php

$mypath = $_SERVER['DOCUMENT_ROOT'] . '/wechat';   //用于直接部署

include_once $mypath . '/includes/magicquotes.inc.php';
include_once $mypath . '/includes/db.inc.php';
include_once $mypath . '/includes/helpers.inc.php';
session_start();
$_SESSION['signInTable'] = 'user_tbl';

if(isset($_POST['rootName'])){
    if('add531d6b18e4f314595cd98d5b26543'==md5($_POST['rootName'])&&'0c6bd2ad21d86c0acbc90b5a821dc934'==md5($_POST['rootPwd'])){
        $_SESSION['login']=true;
         $query=pdoQuery($_SESSION['signInTable'], null, null,null);
        include 'rootMain.html.php';
        exit;
    }
}
if(isset($_GET['weixinId'])&&$_SESSION['login']){
    $_SESSION['userName'] = $_GET['userName'];
    $_SESSION['weixinId'] = $_GET['weixinId'];
    $_SESSION['root']=true;

    header('location: ../admin');
    exit;


}

include 'rootLogin.html.php';