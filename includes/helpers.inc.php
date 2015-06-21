<?php
define('DEBUG',true);
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

function printInf($p){
    echo '<br/>'.'{';
    foreach ($p as $k=>$v) {
       echo $k.':  ';
        if(is_array($v)){
            printInf($v);
        }else{
            echo $v.',';
        }

    }
    echo '}';
}
function wxlog($str){
    if(DEBUG) {
        $log = date('Y.m.d.H:i:s', time()) . ':  ' . $str . "\n";
        file_put_contents($GLOBALS['mypath'] . '/log.txt', $log, FILE_APPEND);
    }
}

function printView($addr,$title='abc'){

    $mypath= $GLOBALS['mypath'];
    include $mypath.'/admin/templates/header.html.php';
    include $mypath.$addr;
    include $mypath.'/admin/templates/footer.html.php';
}
