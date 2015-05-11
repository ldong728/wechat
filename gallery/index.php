<?php
$mypath = $_SERVER['DOCUMENT_ROOT'] . '/wechat';
$myurl = 'http://115.29.202.69/xdsm';
include_once $mypath . '/includes/magicquotes.inc.php';
include_once $mypath . '/includes/db.inc.php';
include_once $mypath . '/includes/helpers.inc.php';

if(isset($_GET['page_offset'])){
    if(isset($_GET['page_down'])){

        $query=pdoQuery('upload_tbl',array('file_path'),null,'limit '.$_GET['page_offset'].', 10');
        $page_index=$_GET['page_offset']+10;
        echo $_GET['page_offset'].'--'.$page_index;
    }else{
        $page_index=$_GET['page_offset']-20;
        if($page_index<0)$page_index=0;
        $query=pdoQuery('upload_tbl',array('file_path'),null,'limit '.$page_index.', 10');
        $page_index+=10;
        echo ($page_index-10).'--'.$page_index;
    }
    include 'index.html.php';
    exit;
}
$page_index=10;
$query=pdoQuery('upload_tbl',array('file_path'),null,'limit 0,10');
include 'index.html.php';




/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/4/24
 * Time: 15:58
 */ 