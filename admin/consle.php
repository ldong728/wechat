<?php
$mypath = $_SERVER['DOCUMENT_ROOT'] . '/xdsm';
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
        $str = getButtonSituation();
        output($str);
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