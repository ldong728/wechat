<?php
$mypath = $_SERVER['DOCUMENT_ROOT'] . '/wechat';
include_once $mypath . '/includes/magicquotes.inc.php';
include_once $mypath . '/includes/db.inc.php';
include_once $mypath.'/includes/xdsmdb.php';
include_once $mypath . '/includes/helpers.inc.php';
include_once $mypath.'/contrller/serveManager.php';
include_once $mypath.'/class/mobilePhoneQuery.php';
include_once $mypath.'/class/trainQuery.php';
session_start();
if(isset($_SESSION['login'])&&$_SESSION['login']) {


    if (isset($_GET['upload'])) {
        include 'upload.html.php';
        exit;

    }
    if (isset($_FILES['loadIn'])) {
        $inf = uploadTempMedia($_FILES['loadIn'], $_POST['type']);
        output($inf);

    }

    if (isset($_GET['edit_gallery'])) {
        $query = pdoQuery('upload_tbl', array('file_path', 'media_id'), null, null);
        include 'editgallery.html.php';
        exit;
    }
    if (isset($_GET['delete_image'])) {
        $sql = 'delete from upload_tbl where media_id="' . $_GET['file_path'] . '"';
        $num = $pdo->exec($sql);
        $path = $mypath . '/tmpmedia/' . $_GET['file_path'] . '.jpg';
        $com = unlink($path);
        header('location: consle.php?edit_gallery');
        exit;
    }
    if(isset($_GET['delete_button'])){
        deleteButton();
        echo 'deleteOk!';
        exit;
    }
    if(isset($_GET['create_button'])){
        createButton();
    }
















    if (isset($_GET['modultest'])) { //模块测试
//        $d=getAllKFInf();

//        echo $d;
        $json_str='{"from":{"0":"oe1xGuKgyV83VyKPKTwWx6jxvNKw"},"me":{"0":"gh_578ec12b4614"},"type":{"0":"text"},"msgId":{"0":"6145970747626711230"},"content":"\u554a\u554a\u554a"}';
        $msg=json_decode($json_str,true);
        echo $msg['from'][0];
        $list = array('touser'=>$msg['from'][0],'type'=>'type');
        $json=json_encode($list);
        echo $json;
        exit;
    }






}else{
    include '../login/index.php';

}


/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/4/21
 * Time: 12:31
 */ 