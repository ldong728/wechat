<?php

$mypath = $_SERVER['DOCUMENT_ROOT'] . '/xdsm';   //用于直接部署

include_once $mypath . '/includes/magicquotes.inc.php';
include_once $mypath . '/includes/db.inc.php';
include_once $mypath . '/includes/helpers.inc.php';
session_start();
$_SESSION['signInTable']='user_tbl';


if(isset($_POST['nameReady'])){  //新用户注册提交
    if($_POST['nameReady']==1&&$_POST['password']==$_POST['password2']){
        $insertId=pdoInsert($_SESSION['signInTable'],array('name'=>$_POST['name'],'password'=>md5($_POST['password'])));
        $_SESSION['login']=true;
        $_SESSION['userName']=$_POST['name'];
        header('location: ../admin');
        exit;
    }else{
        include 'signup.html.php';
        exit;
    }
}
if(isset($_GET['signup'])){
    include 'signup.html.php';
    exit;
}

if(isset($_POST['login'])){//判断登入是否正确
$password=pdoQuery($_SESSION['signInTable'], null, array('name' => $_POST['user_name']),'limit 1');
    if($row=$password->fetch()){
        if(md5($_POST['password'])==$row['password']){
            $_SESSION['login']=true;
            $_SESSION['userName']=$row['user_name'];
            header('location: ../admin');
        }else{
            $error='密码不正确';
            include 'loginError.html.php';
            exit;
        }

    }else{
        $error='用户名不存在';
        include 'loginError.html.php';
        exit;
    }

}
if(isset($_GET['logout'])){
    $_SESSION['login']=false;
    unset($_SESSION);
    session_unset();
    header('location: ../');
    exit;

}


include 'login.html.php'



?>