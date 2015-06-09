<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/5/26
 * Time: 14:33
 */

//wxlog('include joke Ok');
if(preg_match('/笑话/',$msg['content'])){
//    wxlog('joke');
    include_once $mypath.'/class/jokeMaker.php';
    $joke=new jokeMaker();
//    wxlog('jokeMaker');
    $jokeContent=$joke->getJoke();
//    wxlog('get: '.$jokeContent);
    $response=$weixin->prepareTextMsg($msg['from'],$msg['me'],$jokeContent);
    echo $response;
    exit;
}