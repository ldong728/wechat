<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/5/15
 * Time: 13:15
 */
$mypath = $_SERVER['DOCUMENT_ROOT'] . '/wechat';
include_once $mypath . '/includes/magicquotes.inc.php';
include_once $mypath . '/includes/db.inc.php';
include_once $mypath . '/includes/helpers.inc.php';

if(isset($_POST['msgNum'])){
//    wxlog('ajaxRequest recieved');
    $timelimit = (string)(time()-$_POST['stopTime']);
    $data=pdoQuery('wechat_wall_tbl',null,null,'where upload_time>'.$timelimit.' order by upload_time asc limit '.$_POST['msgNum']);
//    $data=pdoQuery('wechat_wall_tbl',null,null,'where upload_time<1000 order by upload_time asc limit '.$_POST['msgNum']);
    $query=array();
    foreach ($data as $row) {
        $query[]=array('user_name'=>$row['user_name'],'sex'=>$row['sex'],'user_icon'=>$row['user_icon'],'content'=>$row['content']
        ,'img_url'=>$row['img_url'],'upload_time'=>$row['upload_time']);

    }
    $jsonData = json_encode($query);
//    wxlog('jsonData:'.$jsonData);
    echo $jsonData;
}