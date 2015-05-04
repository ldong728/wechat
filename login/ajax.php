<?php
$mypath = $_SERVER['DOCUMENT_ROOT'] . '/xdsm';   //用于直接部署
include_once $mypath . '/includes/magicquotes.inc.php';
include_once $mypath . '/includes/db.inc.php';
include_once $mypath . '/includes/helpers.inc.php';
session_start();
if(isset($_POST['checkName'])){
    $ncheck=pdoQuery($_SESSION['signInTable'], array('count(*) as num'), array('name' => $_POST['checkName']), null);
    $row=$ncheck->fetch();
    $num = $row['num'];
    echo $num;
//    echo 0;
}




?>