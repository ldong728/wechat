<?php

try
{
    $xdsmdb = new PDO('mysql:host=shhaijie.gotoftp2.com;dbname=shhaijie', 'shhaijie', '840424');
    $xdsmdb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $xdsmdb->exec('SET NAMES "utf8"');
}
catch (PDOException $e)
{
    $error = 'Unable to init the database server.'.$e->getMessage();
    include 'error.html.php';
    exit();
}
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/4/29
 * Time: 14:43
 */ 