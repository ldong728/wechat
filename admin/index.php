<?php
$mypath = $_SERVER['DOCUMENT_ROOT'] . '/wechat';
include_once $mypath . '/includes/magicquotes.inc.php';
include_once $mypath . '/includes/db.inc.php';
include_once $mypath . '/includes/helpers.inc.php';
session_start();
if(isset($_SESSION['login'])&&$_SESSION['login']){
    include 'index.html.php';
}else{
    header('location: ../login/index.php');

}


