<?php
$mypath = $_SERVER['DOCUMENT_ROOT'] . '/wechat';
include_once $mypath . '/includes/magicquotes.inc.php';
include_once $mypath . '/includes/db.inc.php';
include_once $mypath . '/includes/helpers.inc.php';
include_once $mypath.'/contrller/serveManager.php';
include_once $mypath.'/class/newsEdit.php';
session_start();
if(isset($_SESSION['login'])&&$_SESSION['login']) {

//    if(isset($_GET['uedit'])){
////        $_POST['uEdit'];
//        $id=pdoInsert('news_tbl',array('md5'=>md5($_POST['uEdit']),'weixin_id'=>$_SESSION['weixinId'],'content'=>addslashes($_POST['uEdit']),'update_time'=>time()));
//        $query=pdoQuery('news_tbl',null,array('id'=>$id),null);
//        $row=$query->fetch();
//        output($row['content']);
////        echo $row('content');
//        echo 'OK';
//        exit;
//    }
    //自定义菜单的设置
    if(isset($_GET['menuManage'])){
        if(isset($_GET['menuInfo'])){
            $buttonInf=getMenuInf();
        }
        if(isset($_GET['menuCreate'])){
            printView('/admin/view/menuCreater.html.php');
            exit;
        }



        printView('/admin/view/menuedit.html.php');
        exit;
    }
    if(isset($_GET['tokenInf'])){
        $query = pdoQuery('user_tbl',array('token'),array('weixin_id'=>$_SESSION['weixinId']),' limit 1');
        $row=$query->fetch();
        $token=$row['token'];
        printView('/admin/view/tokenInf.html.php');
        exit;
    }
    if(isset($_GET['getNewsEditor'])){
        $query=pdoQuery('news_tbl',null,array('weixin_id'=>$_SESSION['weixinId']),' limit 20');
        printView('/admin/view/newsEdit.html.php','图文信息编辑');
        exit;
    }
    if(isset($_GET['newsedit'])){

        if(!isset($_SESSION['temp_name'])){
            $_SESSION['temp_name']=getRandStr();
        }
        $news=new newsEdit($_SESSION['temp_name']);

    }

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
    if(isset($_GET['logout'])){
        $_SESSION['login']=false;
        unset($_SESSION);
        session_unset();
        header('location: ../');
    }
    if(isset($_GET['create_button'])){
        createButton();
        exit;
    }

    if(isset($_GET['menu'])){
        $menuInf=getMenuInf();
        $buttonInf=$menuInf['menu']['button'];
        include 'menuedit.html.php';

    }

    if(isset($_GET['del_guess_tbl'])){
        $sql='delete from guess_tbl where weixin_id="'.$_SESSION['weixinId'].'"';
        $pdo->exec($sql);
        header('location: index.php');
    }

    if (isset($_GET['modultest'])) {//功能测试块
        printView('/admin/view/newsEdit.html.php','测试页');
//        unlink("../user_img/gh_904600228e98/eElEEzrE53tgVvwL*");
        exit;

    }

    if(isset($_GET['getContact'])){
        $query=pdoQuery('guess_tbl',null,array('weixin_id'=>$_SESSION['weixinId']),' order by correct_try desc, update_time asc limit 20');
        printView('/admin/view/query.html.php','自动回复设置');

    }
    if(isset($_GET['kfManage'])){
        $data=getKFinf();
        $dataArray=json_decode($data,true);
        printView('/admin/view/kfManage.html.php','客服管理');
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