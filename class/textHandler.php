<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/4/25
 * Time: 10:07
 */
$mypath=$_SERVER['DOCUMENT_ROOT'] . '/wechat';
include_once $mypath . '/includes/magicquotes.inc.php';
include_once $mypath . '/includes/db.inc.php';
include_once $mypath . '/includes/helpers.inc.php';


class textHandler {
    private $recieveText='';

    public function __construct($str){
        $this->$recieveText=$str;
    }
    private function keywordFilter($str){


    }

} 