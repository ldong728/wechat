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
    if(isset($_GET['modulemenu'])){
        $query=pdoQuery('duty_tbl',array('duty'),array('weixin_id'=>$_SESSION['weixinId']),' limit 1');
        $row=$query->fetch();
        $duty=json_decode($row['duty'],true);
        $query=pdoQuery('module_tbl',null,null,' limit 20');
        foreach ($query as $row) {
            $menu[]=array('name'=>$row['name'],'inf'=>$row['inf'],'path'=>$row['path'],'menu_inf'=>$row['menu_inf'],
            'price'=>$row['price'],'uni'=>$row['uni'],'selected'=>(array_search($row['path'],$duty['dutyContent'])>-1 ? 1:0));
        }
        include 'moduleselect.html.php';
        exit;

    }
    if(isset($_POST['moduleset'])){

        if(isset($_POST['mulmodule'])){
            for($i=0;$i<count($_POST['mulmodule']);$i++){
                $modules[]=$_POST['mulmodule'][$i];
            }
        }
        if(isset($_POST['unimodule'])){
            $modules[]=$_POST['unimodule'];
            $menuInf=pdoQuery('module_tbl',array('menu_inf'),array('path'=>$_POST['unimodule']),' limit 1');
            $menu=$menuInf->fetch();
            if($menu['menu_inf']!=null){
                deleteButton();
                creatButton($menu['menu_inf']);
            }
        }
        $prejson=array('dutyContent'=>$modules);
        $json=json_encode($prejson);
        $json=addslashes($json);
        $sql = 'update duty_tbl set duty="'.$json.'" where weixin_id="'.$_SESSION['weixinId'].'"';
        echo $sql;
        $pdo->exec($sql);
        header('location: index.html.php');
        exit;
//        pdoInsert('duty_tbl',array('weixin_id'=>$_SESSION['weixinId'],'duty'=>$json));

    }

    if(isset($_GET['menu'])){
        $menuInf=getMenuInf();
        $buttonInf=$menuInf['menu']['button'];
//        exit;
        include 'menuedit.html.php';

    }
    if(isset($_GET['auto_reply'])){
        if(isset($_GET['reply_type'])){
            $mediaList = getMediaList($_GET['reply_type'],0);
            foreach ($mediaList['item'] as $row) {
                $allList[]=json_encode($row,JSON_UNESCAPED_UNICODE);
            }

        }
        if(isset($_POST['content'])){
            $_POST['key_word']=trim($_POST['key_word']);
            $key=($_POST['key_word']==''? '.': preg_replace('/,|，/','\|',$_POST['key_word']));
            $content=addslashes($_POST['content']);
            pdoInsert('default_reply_tbl',array('weixin_id'=>$_SESSION['weixinId'],'reply_type'=>$_POST['type'],
            'key_word'=>$key,'content'=>$content),' ON DUPLICATE KEY UPDATE content="'.$content.'"');
        }

        include 'autoreply.html.php';
    }
    if(isset($_GET['del_guess_tbl'])){
        $sql='delete from guess_tbl where weixin_id="'.$_SESSION['weixinId'].'"';
        $pdo->exec($sql);
        header('location: index.php');
    }














    if (isset($_GET['modultest'])) {//功能测试块

    }

    if(isset($_GET['getContact'])){
        $query=pdoQuery('guess_tbl',null,array('weixin_id'=>$_SESSION['weixinId']),' order by correct_try desc, update_time asc limit 20');
        include 'query.html.php';

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