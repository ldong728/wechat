<?php
function html($text)
{
	return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function htmlout($text)
{
	echo html($text);
}

function output($string){
    header("Content-Type:text/html; charset=utf-8");
    echo '<p class = "warning">'. $string.'</p>';

}
function formatOutput($string){
//    $str=html($string);
    $str=preg_replace('/___/','<input type="text"/>',$string);
    return $str;

}

function printInf($p,$g,$s){
    echo'</br>'.'$post:'.'</br>';
    foreach ($p as $k=>$v) {
        echo $k.":  ".$v.'</br>';
    }
    echo'</br>'.'$get:'.'</br>';
    foreach ($g as $k=>$v) {
        echo $k.":  ".$v.'</br>';
    }
    echo'</br>'.'$session:'.'</br>';
    foreach ($s as $k=>$v) {
        echo $k.":  ".$v.'</br>';
    }
}
function wxlog($str){
    $log=date('Y.m.d.H:i:s',time()).':  '.$str."\n";
    file_put_contents($GLOBALS['mypath'].'/log.txt',$log,FILE_APPEND);
}

function mytest($s){

    echo $s;
    exit;
}