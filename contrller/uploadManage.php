<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/6/22
 * Time: 15:23
 */
header("Content-Type:text/html;charset=utf-8");
error_reporting( E_ERROR | E_WARNING );

$mypath = $_SERVER['DOCUMENT_ROOT'] . '/wechat';
include_once $mypath . '/includes/magicquotes.inc.php';
include_once $mypath . '/includes/db.inc.php';
include_once $mypath . '/includes/helpers.inc.php';
include_once $mypath . '/class/uploader.php';
session_start();

//上传配置
$config = array(
    "savePath" => "../user_img" ,             //存储文件夹
    "maxSize" => 1000 ,                   //允许的文件最大尺寸，单位KB
    "allowFiles" => array( ".gif" , ".png" , ".jpg" , ".jpeg" , ".bmp" )  //允许的文件格式
);
//上传文件目录
//$Path = "upload/";

//背景保存在临时目录中
//$config[ "savePath" ] = $Path;
if(!isset($_SESSION['temp_name'])){
    $_SESSION['temp_name']=getRandStr();
}
$up = new uploader($_SESSION['weixinId'],$_SESSION['temp_name'], "upfile" , $config );
$type = $_REQUEST['type'];
$callback=$_GET['callback'];

$info = $up->getFileInfo();
/**
 * 返回数据
 */
if($callback) {
    echo '<script>'.$callback.'('.json_encode($info).')</script>';
} else {
    echo json_encode($info);

}