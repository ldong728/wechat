<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/5/15
 * Time: 8:54
 */

$mypath=$_SERVER['DOCUMENT_ROOT'] . '/wechat';

if(isset($_GET['owner'])){
    include 'index.html.php';
}

