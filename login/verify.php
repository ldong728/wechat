<?php

function verify($userId,$vName){
     $query =pdoQuery($_SESSION['signInTable'], array('user_name as name'), array('id' => $userId), null);
    $r=$query->fetch();
    if(md5($r['name'])==$vName){
        return true;
    }else{
        return false;
    }
}





?>