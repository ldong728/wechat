<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/4/30
 * Time: 23:07
 */
$mypath = $_SERVER['DOCUMENT_ROOT'] . '/wechat';
include_once $mypath.'/class/interfaceHandler.php';
class trainQuery {


    public function getlist(){
        $mhandler = new interfaceHandler();
        $data = $mhandler->postByCurl('http://dynamic.12306.cn/otsquery/query/queryRemanentTicketAction.do?method=queryststrainall',
            'date=2015-5-7&fromstation=BJP&tostation=SHH&starttime=00:00--24:00');
        return $data;

    }

} 